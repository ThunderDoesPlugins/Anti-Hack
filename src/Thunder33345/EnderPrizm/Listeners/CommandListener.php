<?php
/** Created By Thunder33345 **/
namespace Thunder33345\EnderPrizm\Listeners;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use Thunder33345\EnderPrizm;
class CommandListener implements Listener
{
	private $loader, $yml, $prefix;

	public function __construct(EnderPrizm\Loader $loader)
	{
		$this->loader = $loader;
		$this->yml = $loader->getYml();
		$this->prefix = $loader->getPrefix();
	}

	public function onCommand(CommandSender $sender, Command $command, $label, array $args)
	{
		switch (strtolower($command->getName())) {
			case "enderprizm":
			case "ep":
			case "e":
				if ($sender instanceof player) {
					if (!$sender->hasPermission('enderprizm.manage')) {
						$sender->sendMessage(TextFormat::GREEN . $this->prefix . " Ender Prizm Made by " . TextFormat::BOLD . "Thunder33345");
						return true;
					}
				}
				if (!isset($args[0])) {
					$sender->sendMessage('Use Help for more info');
					break;
				}
				switch (strtolower($args[0])) {
					case"help":
						break;
					case"status":
						break;
					case"about":
					case"credit":
						$sender->sendMessage(TextFormat::GREEN . $this->prefix . " Ender Prizm (Beta) Made by " . TextFormat::BOLD . "Thunder33345");
						break;
					default:
						$sender->sendMessage('Use Help for more info');
						break;
				}
				break;
		}
		return true;
	}
}