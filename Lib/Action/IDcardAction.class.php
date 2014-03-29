<?php
class IDcardAction extends Action{
    
    function IDcardAction(){
        parent::__construct();
    }
    
    public function index(){
        $id[] = NULL;
        for($i = 0 ;$i < 17; $i++){
            $id[$i] = rand(0,9)%10;
        }
        $c = array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);
        for($i = 0; $i < 17; $i++){
            $x = $id[$i] * $c[$i];
            $sum = $sum + $x;
        }
        $result = $sum % 11;
        if(($sum % 11) == 10) $result = 'x';
        $t = array(1,0,X,9,8,7,6,5,4,3,2);
        $id[17] = $t[$result];
        for($i = 0 ;$i < 18 ; $i++){
            echo $id[$i];
        }
    }
}
?>