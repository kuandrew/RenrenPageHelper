<?php
/**
 * 用于生成表格，同时可防御CSRF攻击
 * 表格生成后会自动生成一个nocsrf字段来检测是否有CSRF攻击
 */
class FormWidget extends Widget{
	   
 
    public function render($data){
        $rand = sha1(md5(base64_encode(rand())));
        session(C('COOKIE_DOMAIN').'nocsrf',$rand);
        //cookie('nocsrf',$rand); 
        $result = '<form action="'.$data['url'].'" class="'.$data['class'].'" method="POST" ><input name="nocsrf" style="display:none;" value="'.$rand.'" />'; 
        return $result;
    }
    
}
?>