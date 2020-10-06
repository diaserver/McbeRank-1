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
        
            
    }
    
    public function onJoin(PlayerJoinEvent $event){
        $url = ('http://be.diamc.kr:3500/api/servers' . $this->db['ip'] . ':' . $this->db['port']);
			$data = (array) json_decode(Internet::getURL($url));
            $rank = (array) $data['rank'];
        
        $event->getPlayer()->sendMessage('§l§b< §f알림 §b> §r§f현재 우리 서버의 순위는 §6($rank)위§r§f 입니다! §r(MCBE RANK 기준)');
    }
}
