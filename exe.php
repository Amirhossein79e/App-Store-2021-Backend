<?php
require_once (__DIR__.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'View.php');
use Appstore\view as view;
error_reporting(E_ALL);

//$method = $_POST['requestCode'];
//$data = str_replace(' ','+',$_POST['data']);
$method = 2;
$data = 'pT1sM5UiAK3R9aBGNhNoRFyzm4G5bSwp00+CN7J5el9T+YV0GV6GDSiV9QfR8djFQ9mmxnDG/ClTJUdkDAsUlm+gIJtoSadJgunocxU++mYhXaPalgUv2ybFG0QpYK8CKNiy2ScaUVAau4Y3S+9lelNpu+3tIJAB87Jd3z6PG/xQDHZ9LeL5DyEP6vMrb0AAmYoyWyFeBjhjZSjieyqEmuvQQ6QXtrBeJt4KbgmIxCoAjZ02H5CtGty15Gpi4ZSsJLZq2LcKKVmDueFibG6vcJDKZIvW8zz86+qG2LYPumJtCdfLRg4Z8uc5IPHAv/tFUkDq2DVfynfoQ3ofVieEEQ==@Z43LtUr44LZCahjK7LTsejPewJwqy+9XkcElrsTaSQ4MH5FtxQiiqIsv3Andn6NwnfIb61mhibss5sYnTbImrokHxMiev0xQBSI66OGPXJbSX3xdHjbhldmGBvVPR8xZ0BxaNfRD/Pd4APSRlISeCr5QC7m3/88Rwtolw3E9+Ip9fW3Wpqec7SpgG8RptcAqvSTq4Urxq4qZMcXYLVXwx3ZAONlhP26wLfwhHD/9K5CCsBEiuzYM4gb9uCb+/70Qo5KClw2ER63RbUI4aWPJclXDV/J499dnnkY4hRuc8ZNepadpdJGQkq11Jmvzawx1PjblCkjRchLDm2Ls2mKsZA==@xnlSFHE+EGk2XoiG4m7/pDQI8c8zbs2XF1zZvmq1zKYlpkpXp4s0WW/0uHwCyp9FKM0Whj6BWxiPQeljY1KiwV9DPBQ/sybB91dYbFXPko+jyrtRnkhrf7h5tE+gpihtDnGQJjmoYC57m/ajfpg6nPeCmQ/UDkbhCkRdnXGPNz13ePRecV6CS0tUi2T9+gAiIWiGPqJC9XwIVBRzXoKl9g==';

if ($method != null && strlen($method) > 0 && $data != null && strlen($data) > 0)
{
    $view = new view\View();
    settype($method,'int');

    switch ($method)
    {
        case 1:
            $view->initToken($data);
            break;

        case 2:
            $view->syncToken($data);
            break;

        default:
            echo 'Incorrect request code';
    }
}


?>