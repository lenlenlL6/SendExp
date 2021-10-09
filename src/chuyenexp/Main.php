<?php

namespace chuyenexp;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\Listener;

use jojoe77777\FormAPI\CustomForm;

class Main extends PluginBase implements Listener {
  
  public function onEnable(){
    $this->getLogger()->info("Chuyển Exp Đã Được Bật");
  }
  
  public function onDisable(){
    $this->getLogger()->info("Chuyển Exp Đã Bị Tắt");
  }
  
  public function onCommand(CommandSender $sender, Command $cmd, String $label, array $args): bool{
    switch($cmd->getName()){
      case "chuyenexp":
        if ($sender instanceof Player){
          $this->ExpUi($sender);
        }else{
          $sender->sendMessage("Pls Use In Game");
        }
    }
    return true;
  }
  
  public function ExpUi($player){
    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $form = $api->createCustomForm(function(Player $player, array $data = null){
      
      if($data === null){
        return true;
      }
      if($data[0] == null){
        $player->sendMessage("§l§c✘ Hãy nhập tên người muốn chuyển EXP");
        return true;
      }
      if($data[0] == $player->getName()){
        $player->sendMessage("§l§c✘ Bạn không thể chuyển EXP cho chính mình");
        return true;
      }
      if($data[1] <= 0){
        $player->sendMessage("§l§c✘ Bạn không thể gửi EXP nhỏ hơn 0");
        return true;
      }
      if($data[1] == null){
        $player->sendMessage("§l§c✘ Hãy nhập số EXP muốn chuyển");
        return true;
      }
      if($data[0] == null and $data[1] == null){
        $player->sendMessage("§l§c✘ Bạn chưa nhập thứ gì cả");
        return true;
      }
      if(!is_numeric($data[1])){
        $player->sendMessage("§l§c✘ Số EXP bạn ghi phải là số");
        return true;
      }
      $playername = $this->getServer()->getPlayer($data[0]);
      $exp = $player->getXpLevel();
      if($playername instanceof Player){
        if($exp >= $data[1]){
       $exp2 = $playername->getXpLevel();
          $player->setXpLevel($exp - $data[1]);
          $player->sendMessage("§l§a✔ Đã chuyển thành công §e" . $data[1] . "§l§a EXP cho §e" . $data[0]);
          $playername->setXpLevel($exp2 + $data[1]);
          $playername->sendMessage("§l§a✔ Người chơi §c" . $player->getName() . "§l§a đã chuyển cho bạn §c" . $data[1] . "§l§a EXP");
        }else{
          $player->sendMessage("§l§c✘ Bạn không đủ §e" . $data[1] . "§l§c EXP để chuyển");
        }
      }else{
        $player->sendMessage("§l§c✘ Người chơi §e" . $data[0] . "§l§c hiện không online");
      }
    });
    $form->setTitle("§l§c〚 CHUYỂN EXP 〛");
    $form->addInput("§l§e▶ Nhập tên người muốn chuyển EXP", "VD: DUY_ONICHAN");
    $form->addInput("§l§e▶ Nhập số EXP muốn chuyển", "0");
    $form->sendToPlayer($player);
    return $form;
  }
}
