<?php
namespace dokidia;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Internet;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;

class McbeRank extends PluginBase implements Listener{
    public function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        $this->config = new Config($this->getDataFolder() . 'Config.yml', Config::YAML, [
            'ip' => $this->getServer()->getIp(),
            'port' => $this->getServer()->getPort()
        ]);
        $this->db = $this->config->getAll();
        $url = json_decode($this->getServerRank(), true);
        
        if(!($url['success'] ?? false)){
            $this->getLogger()->critical('http://be.diamc.kr 에 서버가 등록되어있지 않습니다.');
            $this->getLogger()->notice('http://be.diamc.kr/new 에서 서버를 등록해주세요');
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }
    public function getServerRank() : string{
        return Internet::getURL('http://be.diamc.kr:3500/api/servers' . $this->db['ip'] . ':' . $this->db['port']);
    }
    public function onJoin(PlayerJoinEvent $event){
        $url = json_decode($this->getServerRank(), true);
        $event->getPlayer()->sendMessage('§l§b< §f알림 §b> §r§f현재 우리 서버의 순위는 §6 ' . $url['result'] ['rank'] . ' 위§r§f 입니다! §r(MCBE RANK 기준)');
    }
}
