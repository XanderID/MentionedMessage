<?php

namespace MulqiGaming64\MentionedMessage;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\utils\TextFormat as C;
use pocketmine\utils\Config;

use pocketmine\scheduler\ClosureTask;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;

use MulqiGaming64\MentionedMessage\Commands\MyMessageCommands;
use jojoe77777\FormAPI\SimpleForm;

class MentionedMessage extends PluginBase implements Listener{
    
    /** @var Config $message */
    private $message;
    /** @return array */
    public $messageList = [];
    
    public function onEnable(): void{
    	$this->saveDefaultConfig();
    	if (!class_exists(SimpleForm::class)) {
			$this->getLogger()->error("FormAPI virion not found. Please download MentionedMessage from Poggit-CI");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }
    	$this->message = new Config($this->getDataFolder() . "message.yml", Config::YAML, array());
    	$this->messageList = $this->message->getAll();
    	$this->getScheduler()->scheduleDelayedRepeatingTask(new ClosureTask(
        	function(int $currentTick): void{
            	$this->message->setAll($this->messageList);
				$this->message->save();
            }
        ), 20 * $this->getConfig()->get("save-interval"), 20 * $this->getConfig()->get("save-interval"));
        $timezone = $this->getConfig()->get("timezone");
        if(!isset($timezone)){
        	$this->setTimezone("asia/jakarta"); // My Country :)
        } else {
        	$this->setTimezone($timezone);
        }
        $this->getServer()->getCommandMap()->register("MentionedMessage", new MyMessageCommands("mymessage", $this));
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    
    public function onDisable(): void{
    	// Save all Mentioned Player Message
    	$this->message->setAll($this->messageList);
		$this->message->save();
	}
	
	private function setTimezone(string $timezone): bool{
		date_default_timezone_set($timezone);
		$this->getServer()->getLogger()->notice("Timezone Updated To " . $timezone);
		return true;
	}
    
    public function onChat(PlayerChatEvent $event){
    	if ($event->isCancelled()) return;
    	$player = $event->getPlayer();
    	$msg = $event->getMessage();
    	$this->addMentionedPlayer($player, $msg);
    }
    
    public function onJoin(PlayerJoinEvent $event){
    	$player = $event->getPlayer();
    	$name = strtolower($player->getName());
    	if(!isset($this->messageList[$name])){
    		$this->messageList[$name] = [];
    	}
    }
    
    private function addMentionedPlayer(Player $player, string $message): bool{
    	$name = $player->getName();
    	$lower = strtolower($name);
    	if(!preg_match("/@/i", $message)) {
    		return false;
    	}
		$exp = explode(" ", $message);
		foreach($exp as $expmsg){
			$mentioned = explode(" @", $expmsg);
			foreach($mentioned as $mention){
				if(preg_match("/@/i", $mention)){
					$tagged = preg_replace("/@/i", "", $mention);
					if($tagged){
						if($this->getServer()->hasOfflinePlayerData($tagged)){ // check if the player has been on the server or not, this helps clean up trash
							$id = mt_rand(0, 10000000000); // actually this is not needed, it's just for the front id only
							$formated = $this->replaceTag($player, $message);
							$this->messageList[strtolower($tagged)][$id]= ["message" => $formated];
						}
					}
				}
			}
		}
		return true;
	}
	
	public function myMessage(Player $player): bool{
		$form = new SimpleForm(function (Player $player, int $data = null){
            if ($data === null) {
                return false;
            }
           switch($data){
           	case 0:
           	break;
           	case 1:
           	$name = strtolower($player->getName());
           	$msg = count($this->messageList[$name]);
           	$player->sendMessage(C::GREEN . "successfully deleted " . C::WHITE . $msg . " messages");
           	$this->messageList[$name] = [];
           	break;
           }
        });
        $form->setTitle($player->getName() . " Message");
        $form->setContent($this->getMMessage($player));
        $form->addButton(C::BOLD . C::RED . "Close", 0, "textures/blocks/barrier");
        $form->addButton(C::BOLD . C::RED . "Clear All", 0, "textures/ui/icon_trash");
        $form->sendToPlayer($player);
        return true;
	}
	
	private function getMMessage(Player $player): string{
		$name = $player->getName();
		$lower = strtolower($name);
		$content = $this->messageList[$lower];
		$last = count($content); //For remove new line in last line
		if($last == 0){
			return "You haven't received a message from the person who mentioned you";
		}
		$count = 1; // For remove new line in last line
		$result = C::GREEN . "You Have Total " . C::WHITE . $last . C::GREEN . " Message" . C::EOL; // For to be accessible
		foreach($content as $id => $text){
			if($count <= $last){
				$result .= $text["message"] . C::EOL;
			} else {
				$result .= $text["message"];
			}
			$count++;
		}
		return $result;
	}
	
	public function replaceTag(Player $player, string $message): string{
		$msg = $this->getConfig()->get("mention-message");
		$msg = str_replace("{YEARS}", date("Y"), $msg);
		$msg = str_replace("{MONTH}", date("m"), $msg);
		$msg = str_replace("{DATE}", date("d"), $msg);
		$msg = str_replace("{HOURS}", date("H"), $msg);
		$msg = str_replace("{MINUTES}", date("i"), $msg);
		$msg = str_replace("{NAME}", $player->getName(), $msg);
		$msg = str_replace("{MSG}", $message, $msg);
		return $msg;
	}
}
