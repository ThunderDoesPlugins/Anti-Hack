<?php
namespace Thunder33345\EnderPrizm;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use Thunder33345\EnderPrizm\Listeners;

class Loader extends PluginBase implements Listener
{
	private $yml, $prefix = "[EnderPrizm]";

	public function onLoad()
	{
		if (!is_dir($this->getDataFolder())) {
			mkdir($this->getDataFolder(), 0777, true);
		}
	}

	public function onEnable()
	{
		$this->saveDefaultConfig();
		$this->yml = $this->getConfig()->getAll();
		$this->getServer()->getPluginManager()->registerEvents(new Listeners\CommandListener($this), $this);
		if ($this->yml['Log']) {
			if (!is_dir($this->getDataFolder() . "/log/")) {
				mkdir($this->getDataFolder() . "/log/", 0777, true);
			}
			$this->getLogger()->info(TextFormat::GREEN . 'Logger enabled!');
		}

		$this->getLogger()->info(TextFormat::GREEN . "Ender Prizm by Thunder33345 Loaded!");
	}

	public function onDisable()
	{
		$this->getLogger()->info(TextFormat::GREEN . "Ender Prizm by Thunder33345 Unloaded!");
	}

	public function logToFile($name, $text)
	{
		if ($this->yml["Log"]) {
			$file = $this->getDataFolder() . "/log/" . $name . ".yml";
			$fh = fopen($file, 'a');
			$time = gmdate('Y-m-d h:i:s \G\M\T');
			fwrite($fh, $time . " " . $text . "\n");
			fclose($fh);
		}
	}

	public function getYml()
	{
		return $this->yml;
	}

	public function getPrefix()
	{
		return $this->prefix;
	}
}