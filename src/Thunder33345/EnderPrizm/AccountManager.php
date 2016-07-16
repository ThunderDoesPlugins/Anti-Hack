
namespace Thunder33345\EnderPrizm;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
//use pocketmine\permission\BanList;
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
	public function removeFile($player){
		$player = strtolower($player);
		unlink($this->getFilePath($player));
	}
	//public function (){}
	public function isBanned($player){
		$player = strtolower($player);
		$this->checkExisted($player);
		$file = new Config($this->getDataFolder() . "Players/" . $player . ".yml", Config::YAML);
		$file->save();
		return $file->get("IsBanned");
	}
	public function setBanned($player){
		$player = strtolower($player);
		$this->checkExisted($player);
		$file = new Config($this->getDataFolder() . "Players/" . $player . ".yml", Config::YAML);
		$file->set("IsBanned","true");
		$file->save();
	}
	public function removeBan($player){
		$player = strtolower($player);
		$this->checkExisted($player);
		$file = new Config($this->getDataFolder() . "Players/" . $player . ".yml", Config::YAML);
		$file->set("IsBanned","false");
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
}