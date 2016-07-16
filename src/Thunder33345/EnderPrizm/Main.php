<?php
namespace Thunder33345\EnderPrizm;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\utils\TextFormat as TF;

//define('prefix', '[EnderPrizm]', true);

class Main extends PluginBase implements Listener
{
	public $yml, $api, $getAccountManager;

	/*
	public function __construct()
	{
		//define('prefix', '[EnderPrizm]', true);
		$this->getAccountManager = new AccountManager();
	}
	*/

	public function onLoad()
	{
		if (!is_dir($this->getDataFolder())) {
			mkdir($this->getDataFolder(), 0777,true);
		}
	}

	public function onEnable()
	{
		define('prefix', '[EnderPrizm]', true);
		$this->saveDefaultConfig();
		$this->yml = $this->getConfig()->getAll();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		if ($this->yml['AntiFly']) {
			$this->getLogger()->info(TF::GREEN . 'Loading Anti Fly Module...');
			$this->getServer()->getPluginManager()->registerEvents(new FlyCheck($this), $this);
		}
		if ($this->yml['AntiSpeed']) {
			$this->getLogger()->info(TF::GREEN . 'Loading Anti Speed Module...');
			$this->getServer()->getPluginManager()->registerEvents(new SpeedCheck($this), $this);
		}
		if ($this->yml['Log']) {
			$this->getLogger()->info(TF::GREEN . 'Logger enabled!');
			mkdir($this->getDataFolder() . "/log/", 0777,true);
		}
		$this->getAccountManager = new AccountManager();
		$this->getLogger()->info(TextFormat::GREEN . "Ender Prizm by Thunder33345 Loaded!");
	}

	public function logToFile($name, $text)
	{
		if ($this->yml["Log"]) {
			$file = $this->getDataFolder() . "/log/" . $name . ".yml";
			$fh = fopen($file, 'a');
			$time = gmdate('Y-m-d h:i:s \G\M\T');
			fwrite($fh, $time . " " . $text . "\n");
		}
	}

	public function onDisable()
	{
		$this->getLogger()->info(TextFormat::RED . "Ender Prizm by Thunder33345 Unloaded!");
	}

	public function onPlayerJoin(PlayerJoinEvent $event)
	{
	}

	public function onCommand(CommandSender $sender, Command $command, $label, array $args)
	{
		switch (strtolower($command->getName())) {
			case "enderprizm":
			case "ep":
			case "e":
				if ($sender instanceof player) {
					if (!$sender->hasPermission('enderprizm.admin')) {
						$sender->sendMessage(TF::GREEN . Prefix . " Ender Prizm Made by " . TF::BOLD . "Thunder33345");
						return true;
					}
				}
				if (!isset($args[0])) {
					$sender->sendMessage('Use Help for more info');
				}
				switch (strtolower($args[0])) {
					case"help":
						$sender->sendMessage(
							"----" . Prefix . "----\n" .
							"help - show help\n" .
							"status - show enabled module\n" .
							"about/credit - show credit");
						break;
					case"status":
						$sender->sendMessage(Prefix . " Status");
						if ($this->yml['AntiFly']) {
							$sender->sendMessage(Prefix . " Anti Fly = ON");
						} else {
							$sender->sendMessage(Prefix . " Anti Fly = OFF");
						}
						if ($this->yml['AntiSpeed']) {
							$sender->sendMessage(Prefix . " Anti Speed = ON");
						} else {
							$sender->sendMessage(Prefix . " Anti Speed = OFF");
						}
						if ($this->yml['Log']) {
							$sender->sendMessage(Prefix . " Logger = ON");
						} else {
							$sender->sendMessage(Prefix . " Logger = OFF");
						}
						break;
					case"about":
					case"credit":
						$sender->sendMessage(TF::GREEN . Prefix . " Ender Prizm Made by " . TF::BOLD . "Thunder33345");
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

class AccountManager extends Main
{
	public function loadAccount($player)
	{
		$player = strtolower($player);
		$this->checkExisted($player);
		$file = new Config($this->getDataFolder() . "Players/" . $player . ".yml", Config::YAML);
		return $file->getAll();
	}

	public function checkExisted($player)
	{
		$player = strtolower($player);
		if (!file_exists($this->getFilePath($player))) {
			$this->createAccount($player);
			return true;
		}
		return true;
	}

	public function getFilePath($player)
	{
		$player = strtolower($player);
		return $this->getDataFolder() . "Players/" . $player . ".yml";
	}

	public function createAccount($player, $force = false)
	{
		$player = strtolower($player);
		if (!$force) {
			if (file_exists($this->getFilePath($player))) {
				return true;
			}
		}
		$file = new Config($this->getDataFolder() . "Players/" . $player . ".yml", Config::YAML);
		$file->set("IsBanned", "false");
		$file->set("Points", "0");
		$file->set("Speed", "0");
		$file->set("Fly", "0");
		//$file->set("", "");
		$file->save();
		return true;
	}

	public function addPoint($player, $point = 1)
	{
		$player = strtolower($player);
		$this->checkExisted($player);
		$file = new Config($this->getDataFolder() . "Players/" . $player . ".yml", Config::YAML);
		$points = $this->getPoint($player);
		$total = $points + $point;
		$file->set("Points", $total);
		$file->save();
	}

	public function getPoint($player)
	{
		$player = strtolower($player);
		$this->checkExisted($player);
		$file = new Config($this->getDataFolder() . "Players/" . $player . ".yml", Config::YAML);
		$file->save();
		return $file->get("Points");
	}

	public function deductPoint($player, $point = 0)
	{
		$player = strtolower($player);
		$this->checkExisted($player);
		$file = new Config($this->getDataFolder() . "Players/" . $player . ".yml", Config::YAML);
		$points = $this->getPoint($player);
		$new = $points - $point;
		$file->set("Points", $new);
		$file->save();
	}

	public function clearPoint($player)
	{
		$player = strtolower($player);
		$this->checkExisted($player);
		$file = new Config($this->getDataFolder() . "Players/" . $player . ".yml", Config::YAML);
		$file->set("Points", "0");
		$file->save();
	}

	public function removeFile($player)
	{
		$player = strtolower($player);
		unlink($this->getFilePath($player));
	}

	//public function (){}
	public function isBanned($player)
	{
		$player = strtolower($player);
		$this->checkExisted($player);
		$file = new Config($this->getDataFolder() . "Players/" . $player . ".yml", Config::YAML);
		$file->save();
		return $file->get("IsBanned");
	}

	public function removeBan($player)
	{
		$player = strtolower($player);
		$this->checkExisted($player);
		$file = new Config($this->getDataFolder() . "Players/" . $player . ".yml", Config::YAML);
		$file->set("IsBanned", "false");
		$file->save();
	}

	public function banPlayer($player, $reason, $expire)
	{
		$player = strtolower($player);
		if ($player instanceof Player) {
			$this->setBanned($player);
			$this->getServer()->getNameBans()->addBan($player, $reason, $expire, '[Plugin:EnderPrizm]');
			$this->getServer()->getIPBans()->addBan($player, $reason, $expire, '[Plugin:EnderPrizm]');
		}
	}

	public function setBanned($player)
	{
		$player = strtolower($player);
		$this->checkExisted($player);
		$file = new Config($this->getDataFolder() . "Players/" . $player . ".yml", Config::YAML);
		$file->set("IsBanned", "true");
		$file->save();
	}
}