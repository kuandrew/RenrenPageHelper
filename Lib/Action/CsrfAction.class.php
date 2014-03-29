<?php
class CsrfAction extends Action{

    public function CsrfAction(){
        parent::__construct();
    }
    /**
     * 判断是否发生Csrf攻击，如果是的话返回TRUE，不是则为FALSE
     */
    public function isCsrf(){
        if(!$this->isPost()){
            return;
        }
        if(!session(C('COOKIE_DOMAIN').'nocsrf') or !$this->_post('nocsrf')){
            $this->error('<script>alert("亲，你可能正遭受攻击");</script>亲，你可能正遭受CSRF攻击',C('site_url'));
            return 1;
        }
        if(session(C('COOKIE_DOMAIN').'nocsrf') == $this->_post('nocsrf')){
            return;
        }
    }
    

}
?>