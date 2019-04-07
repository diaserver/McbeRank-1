<?php
namespace DokiDia;

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
            $this->getLogger()->critical('https://mcbe.cf 에 서버가 등록되어있지 않습니다.');
            $this->getLogger()->notice('https://mcbe.cf 에서 로그인 후 서버를 등록해주세요');
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }
    public function getServerRank() : string{
        return Internet::getURL('https://api.mcbe.cf/getServer/' . $this->db['ip'] . ':' . $this->db['port']);
    }
    public function onJoin(PlayerJoinEvent $event){
        $url = json_decode($this->getServerRank(), true);
        $event->getPlayer()->sendMessage('§l§f< §b서버순위 §f> §l§e현재 우리 서버의 순위는§o§a ' . $url['result'] ['rank'] . ' 위§r§l§e 입니다! §r(MCBE RANK 기준)');
    }
}
