<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Helper functions para o módulo Gestão Financeira
 */

if (!function_exists('format_currency_br')) {
    /**
     * Formatar valor monetário para padrão brasileiro
     */
    function format_currency_br($value, $symbol = 'R$ ')
    {
        return $symbol . number_format($value, 2, ',', '.');
    }
}

if (!function_exists('format_cpf_cnpj')) {
    /**
     * Formatar CPF ou CNPJ
     */
    function format_cpf_cnpj($document)
    {
        $document = preg_replace('/[^0-9]/', '', $document);
        
        if (strlen($document) == 11) {
            // CPF
            return substr($document, 0, 3) . '.' . 
                   substr($document, 3, 3) . '.' . 
                   substr($document, 6, 3) . '-' . 
                   substr($document, 9, 2);
        } elseif (strlen($document) == 14) {
            // CNPJ
            return substr($document, 0, 2) . '.' . 
                   substr($document, 2, 3) . '.' . 
                   substr($document, 5, 3) . '/' . 
                   substr($document, 8, 4) . '-' . 
                   substr($document, 12, 2);
        }
        
        return $document;
    }
}

if (!function_exists('validate_cpf')) {
    /**
     * Validar CPF
     */
    function validate_cpf($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        
        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        
        return true;
    }
}

if (!function_exists('validate_cnpj')) {
    /**
     * Validar CNPJ
     */
    function validate_cnpj($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        
        if (strlen($cnpj) != 14) {
            return false;
        }
        
        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }
        
        // Calcula os dígitos verificadores
        $length = strlen($cnpj) - 2;
        $numbers = substr($cnpj, 0, $length);
        $digits = substr($cnpj, $length);
        $sum = 0;
        $pos = $length - 7;
        
        for ($i = $length; $i >= 1; $i--) {
            $sum += $numbers[$length - $i] * $pos--;
            if ($pos < 2) {
                $pos = 9;
            }
        }
        
        $result = $sum % 11 < 2 ? 0 : 11 - $sum % 11;
        if ($result != $digits[0]) {
            return false;
        }
        
        $length = $length + 1;
        $numbers = substr($cnpj, 0, $length);
        $sum = 0;
        $pos = $length - 7;
        
        for ($i = $length; $i >= 1; $i--) {
            $sum += $numbers[$length - $i] * $pos--;
            if ($pos < 2) {
                $pos = 9;
            }
        }
        
        $result = $sum % 11 < 2 ? 0 : 11 - $sum % 11;
        
        return $result == $digits[1];
    }
}

if (!function_exists('get_status_badge')) {
    /**
     * Retornar badge HTML para status
     */
    function get_status_badge($status)
    {
        $badges = [
            'A Pagar' => 'warning',
            'Pago' => 'success',
            'A Receber' => 'info',
            'Recebido' => 'success',
            'Cancelado' => 'danger',
            'Ativo' => 'success',
            'Inativo' => 'danger',
            'Quitado' => 'success',
            'Aberta' => 'warning',
            'Paga' => 'success'
        ];
        
        $class = isset($badges[$status]) ? $badges[$status] : 'default';
        return '<span class="label label-' . $class . '">' . $status . '</span>';
    }
}

if (!function_exists('calculate_percentage')) {
    /**
     * Calcular percentual
     */
    function calculate_percentage($value, $total, $decimals = 2)
    {
        if ($total == 0) {
            return 0;
        }
        
        return round(($value / $total) * 100, $decimals);
    }
}

if (!function_exists('format_phone_br')) {
    /**
     * Formatar telefone brasileiro
     */
    function format_phone_br($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (strlen($phone) == 11) {
            return '(' . substr($phone, 0, 2) . ') ' . 
                   substr($phone, 2, 5) . '-' . 
                   substr($phone, 7, 4);
        } elseif (strlen($phone) == 10) {
            return '(' . substr($phone, 0, 2) . ') ' . 
                   substr($phone, 2, 4) . '-' . 
                   substr($phone, 6, 4);
        }
        
        return $phone;
    }
}

if (!function_exists('get_animal_categories')) {
    /**
     * Retornar categorias de animais
     */
    function get_animal_categories()
    {
        return [
            'Garrotes' => _l('gf_categoria_garrotes'),
            'Novilhas' => _l('gf_categoria_novilhas'),
            'Bezerros' => _l('gf_categoria_bezerros'),
            'Vacas' => _l('gf_categoria_vacas'),
            'Touros' => _l('gf_categoria_touros')
        ];
    }
}

if (!function_exists('calculate_days_between')) {
    /**
     * Calcular dias entre duas datas
     */
    function calculate_days_between($date1, $date2)
    {
        $datetime1 = new DateTime($date1);
        $datetime2 = new DateTime($date2);
        $interval = $datetime1->diff($datetime2);
        
        return $interval->days;
    }
}

if (!function_exists('is_overdue')) {
    /**
     * Verificar se uma data está vencida
     */
    function is_overdue($date)
    {
        return strtotime($date) < strtotime(date('Y-m-d'));
    }
}

if (!function_exists('get_overdue_class')) {
    /**
     * Retornar classe CSS para itens vencidos
     */
    function get_overdue_class($date, $status = null)
    {
        if ($status && in_array($status, ['Pago', 'Recebido', 'Quitado'])) {
            return '';
        }
        
        if (is_overdue($date)) {
            return 'text-danger';
        }
        
        $days_to_due = calculate_days_between(date('Y-m-d'), $date);
        if ($days_to_due <= 7) {
            return 'text-warning';
        }
        
        return '';
    }
}



if (!function_exists('log_activity')) {
    /**
     * Registrar atividade no log
     */
    function log_activity($table, $record_id, $action, $old_data = null, $new_data = null)
    {
        $CI = &get_instance();
        
        $log_data = [
            'usuario_id' => get_staff_user_id(),
            'tabela' => $table,
            'registro_id' => $record_id,
            'acao' => $action,
            'dados_anteriores' => $old_data ? json_encode($old_data) : null,
            'dados_novos' => $new_data ? json_encode($new_data) : null,
            'ip_address' => $CI->input->ip_address(),
            'user_agent' => $CI->input->user_agent(),
            'data_atividade' => date('Y-m-d H:i:s')
        ];
        
        $CI->db->insert(db_prefix() . 'gf_log_atividades', $log_data);
    }
}

