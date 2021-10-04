<?php

declare (strict_types=1);
namespace Refactorio\TemporaryVariable\Model;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall as PHPFuncCall;
use \Exception;

class FuncCall extends NoopModel
{
    private $removeVariables = [];
    private $saveVariables = [];
    private $saveAll = false;
    const FUNCTION_WITH_LINK = [
        'apcu_dec' => [2],
        'apcu_fetch' => [1],
        'apcu_inc' => [2],
        'apc_dec' => [2],
        'apc_fetch' => [1],
        'apc_inc' => [2],
        'array_multisort' => [0],
        'array_pop' => [0],
        'array_push' => [0],
        'array_shift' => [0],
        'array_splice' => [0],
        'array_unshift' => [0],
        'array_walk' => [0],
        'array_walk_recursive' => [0],
        'arsort' => [0],
        'asort' => [0],
        'call_user_method' => [1],
        'call_user_method_array' => [1],
        'curl_multi_exec' => [1],
        'curl_multi_info_read' => [1],
        'dbplus_curr' => [1],
        'dbplus_first' => [1],
        'dbplus_info' => [2],
        'dbplus_last' => [1],
        'dbplus_next' => [1],
        'dbplus_prev' => [1],
        'dbplus_tremove' => [2],
        'dns_get_record' => [2,3],
        'each' => [0],
        'enchant_dict_quick_check' => [2],
        'end' => [0],
        'ereg' => [2],
        'eregi' => [2],
        'exec' => [1,2],
        'exif_thumbnail' => [1,2,3],
        'expect_expectl' => [2],
        'extract' => [0],
        'flock' => [2],
        'fscanf' => [2],
        'fsockopen' => [2,3],
        'ftp_alloc' => [2],
        'getimagesize' => [1],
        'getimagesizefromstring' => [1],
        'getmxrr' => [1,2],
        'getopt' => [2],
        'gnupg_decryptverify' => [2],
        'gnupg_verify' => [3],
        'grapheme_extract' => [4],
        'headers_sent' => [0,1],
        'idn_to_ascii' => [3],
        'idn_to_utf8' => [3],
        'is_callable' => [2],
        'krsort' => [0],
        'ksort' => [0],
        'ldap_control_paged_result_response' => [2,3],
        'ldap_exop' => [4,5],
        'ldap_exop_passwd' => [4],
        'ldap_get_option' => [2],
        'ldap_parse_exop' => [2,3],
        'ldap_parse_reference' => [2],
        'ldap_parse_result' => [2,3,4,5,6],
        'maxdb_stmt_bind_param' => [1,2,3,5,6,9,11],
        'maxdb_stmt_bind_result' => [1,2,3,4],
        'mb_convert_variables' => [2,3],
        'mb_ereg' => [2],
        'mb_eregi' => [2],
        'mb_parse_str' => [1],
        'mqseries_back' => [1,2],
        'mqseries_begin' => [2,3],
        'mqseries_close' => [3,4],
        'mqseries_cmit' => [1,2],
        'mqseries_conn' => [1,2,3],
        'mqseries_connx' => [1,2,3,4],
        'mqseries_disc' => [1,2],
        'mqseries_get' => [2,3,4,5,6,7,8],
        'mqseries_inq' => [5,7,8,9],
        'mqseries_open' => [1,3,4,5],
        'mqseries_put' => [2,3,5,6],
        'mqseries_put1' => [1,2,3,5,6],
        'mqseries_set' => [8,9],
        'msg_receive' => [2,4,7],
        'msg_send' => [5],
        'mssql_bind' => [2],
        'mysqlnd_uh_convert_to_mysqlnd' => [0],
        'mysqlnd_uh_set_connection_proxy' => [0,1],
        'mysqlnd_uh_set_statement_proxy' => [0],
        'm_completeauthorizations' => [1],
        'natcasesort' => [0],
        'natsort' => [0],
        'ncurses_color_content' => [1,2,3],
        'ncurses_getmaxyx' => [1,2],
        'ncurses_getmouse' => [0],
        'ncurses_getyx' => [1,2],
        'ncurses_instr' => [0],
        'ncurses_mousemask' => [1],
        'ncurses_mouse_trafo' => [0,1],
        'ncurses_pair_content' => [1,2],
        'ncurses_wmouse_trafo' => [1,2],
        'newt_button_bar' => [0],
        'newt_form_run' => [1],
        'newt_get_screen_size' => [0,1],
        'newt_grid_get_size' => [1,2],
        'newt_reflow_text' => [4,5],
        'newt_win_entries' => [6],
        'newt_win_menu' => [7],
        'next' => [0],
        'oci_bind_array_by_name' => [2],
        'oci_bind_by_name' => [2],
        'oci_define_by_name' => [2],
        'oci_fetch_all' => [1],
        'odbc_fetch_into' => [1],
        'openssl_csr_export' => [1],
        'openssl_csr_new' => [1],
        'openssl_encrypt' => [5],
        'openssl_open' => [1],
        'openssl_pkcs7_read' => [1],
        'openssl_pkcs12_export' => [1],
        'openssl_pkcs12_read' => [1],
        'openssl_pkey_export' => [1],
        'openssl_private_decrypt' => [1],
        'openssl_private_encrypt' => [1],
        'openssl_public_decrypt' => [1],
        'openssl_public_encrypt' => [1],
        'openssl_random_pseudo_bytes' => [1],
        'openssl_seal' => [1,2,5],
        'openssl_sign' => [1],
        'openssl_spki_export' => [0],
        'openssl_spki_export_challenge' => [0],
        'openssl_spki_new' => [0,1],
        'openssl_spki_verify' => [0],
        'openssl_x509_export' => [1],
        'parsekit_compile_file' => [1],
        'parsekit_compile_string' => [1],
        'parse_str' => [1],
        'passthru' => [1],
        'pcntl_sigprocmask' => [2],
        'pcntl_sigtimedwait' => [1],
        'pcntl_sigwaitinfo' => [1],
        'pcntl_wait' => [0,2],
        'pcntl_waitpid' => [1,3],
        'pfsockopen' => [2,3],
        'php_check_syntax' => [1],
        'preg_filter' => [4],
        'preg_match' => [2],
        'preg_match_all' => [2],
        'preg_replace' => [4],
        'preg_replace_callback' => [4],
        'preg_replace_callback_array' => [3],
        'prev' => [0],
        'proc_open' => [2],
        'reset' => [0],
        'rsort' => [0],
        'settype' => [0],
        'shuffle' => [0],
        'similar_text' => [2],
        'socket_create_pair' => [3],
        'socket_getpeername' => [1,2],
        'socket_getsockname' => [1,2],
        'socket_recv' => [1],
        'socket_recvfrom' => [1,4,5],
        'socket_recvmsg' => [1],
        'socket_select' => [0,1,2],
        'sodium_add' => [0],
        'sodium_crypto_generichash_final' => [0],
        'sodium_crypto_generichash_update' => [0],
        'sodium_crypto_secretstream_xchacha20poly1305_pull' => [0],
        'sodium_crypto_secretstream_xchacha20poly1305_push' => [0],
        'sodium_crypto_secretstream_xchacha20poly1305_rekey' => [0],
        'sodium_increment' => [0],
        'sodium_memzero' => [0],
        'sort' => [0],
        'sqlite_exec' => [2,6],
        'sqlite_factory' => [2],
        'sqlite_open' => [2,5],
        'sqlite_popen' => [2],
        'sqlite_query' => [0,3,7,10],
        'sqlite_unbuffered_query' => [0,3,7,10],
        'sscanf' => [2],
        'stream_select' => [0,1,2],
        'stream_socket_accept' => [2],
        'stream_socket_client' => [1,2],
        'stream_socket_recvfrom' => [3],
        'stream_socket_server' => [1,2],
        'str_ireplace' => [3],
        'str_replace' => [3],
        'swoole_client_select' => [0,1,2],
        'swoole_select' => [0,1,2],
        'system' => [1],
        'taint' => [0],
        'uasort' => [0],
        'uksort' => [0],
        'untaint' => [0],
        'uopz_add_function' => [2,6,7],
        'uopz_del_function' => [3],
        'usort' => [0],
        'wincache_ucache_dec' => [2],
        'wincache_ucache_get' => [1],
        'wincache_ucache_inc' => [2],
        'xdiff_string_merge3' => [3],
        'xdiff_string_patch' => [3],
        'xmlrpc_decode_request' => [1],
        'xmlrpc_set_type' => [0],
        'xml_parse_into_struct' => [2,3],
        'xml_set_object' => [1],
        'yaml_parse' => [2],
        'yaml_parse_file' => [2],
        'yaml_parse_url' => [2],
        'yaz_ccl_parse' => [2],
        'yaz_hits' => [1],
        'yaz_scan_result' => [1],
        'yaz_wait' => [0],
    ];

    const COMPACT_HANDLERS = [
        'Scalar_String' => 'saveScalarString',
        'Expr_Array' => 'openArray',
        'Expr_Variable' => 'openVariable',
        'Expr_MethodCall' => 'funcCall',
        'Expr_FuncCall' => 'funcCall',
    ];

    public function __construct(PHPFuncCall $node)
    {
        parent::__construct($node);
        $this->parse($node);
    }

    public function saveAllParameters() : bool
    {
        return $this->saveAll;
    }

    public function getSaveVariables() : array
    {
        return $this->saveVariables;
    }

    public function getRemoveVariables() : array
    {
        return $this->removeVariables;
    }

    private function calcValuesFromArray(array $array)
    {
        foreach($array as $val) {
            $method = self::COMPACT_HANDLERS[$val->value->getType()];
            $this->$method($val->value);
        }
    }

    private function getLinkVariables() : array
    {
        $result = [];
        if($this->getNode()->name->getType() != 'Name'
        || !key_exists($this->getNode()->name->toString(), self::FUNCTION_WITH_LINK)) {
            return $result;
        }
        foreach(self::FUNCTION_WITH_LINK[$this->getNode()->name->toString()] as $position) {
            if(key_exists($position, $this->getNode()->args)) {
                if($this->getNode()->args[$position]->value->getType() != 'Expr_Variable') {
                    throw new Exception("$position can be variable");
                }
                $result[] = $this->getNode()->args[$position]->value->name;
            }
        }
        return $result;
    }

    private function saveScalarString(Node $node)
    {
        $this->saveVariables[] = $node->value;
    }

    private function openArray(Node $node)
    {
        $this->calcValuesFromArray($node->items);
    }

    private function openVariable(Node $node)
    {
        $this->saveAll = true;
        $this->removeVariables[] = $node->name;
    }

    private function funcCall()
    {
        $this->saveAll = true;
    }

    private function parse(PHPFuncCall $node)
    {
        if($node->name == 'compact') {
            $this->calcValuesFromArray($node->args);
            return;
        }
        $this->saveVariables = $this->getLinkVariables();
    }
}