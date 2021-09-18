<?php

namespace MulqiGaming64\MentionedMessage\Commands;

use MulqiGaming64\MentionedMessage\MentionedMessage;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class MyMessageCommands extends PluginCommand {

	/** @var MentionedMessage $plugin */
    private $plugin;

    /**
     * MyMessageCommands constructor.
     * @param MentionedMessage $plugin
     */
    public function __construct(string $cmd, MentionedMessage $plugin) {
		parent::__construct($cmd, $plugin);
		$this->setAliases(["mm"]);
		$this->setDescription("My Message");
        $this->plugin = $plugin;
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
    	if(!$sender instanceof Player){
        	$sender->sendMessage("Use Commands In Game Please");
        	return false;
        }
    	$this->plugin->myMessage($sender);
		return true;
    }
}
