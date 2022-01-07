<?php

namespace MulqiGaming64\MentionedMessage\Commands;

use MulqiGaming64\MentionedMessage\MentionedMessage;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;

class MyMessageCommands extends Command implements PluginOwned {

	/** @var MentionedMessage $plugin */
    private $plugin;

    /**
     * MyMessageCommands constructor.
     * @param MentionedMessage $plugin
     */
    public function __construct(MentionedMessage $plugin) {
		$this->plugin = $plugin;
		parent::__construct("mymessage", "My Mentioned Message", "/mm", ["mm"]);
        $this->setPermission("mentionedmessage.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool{
    	if(!$sender instanceof Player){
    		$sender->sendMessage("Use Commands in Game");
    		return false;
    	}
    	if (!$this->testPermission($sender)) return false;
    	$this->getOwningPlugin()->myMessage($sender);
        return true;
	}
	
	public function getOwningPlugin(): MentionedMessage{
        return $this->plugin;
    }
}
