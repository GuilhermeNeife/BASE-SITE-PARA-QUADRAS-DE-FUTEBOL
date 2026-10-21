<?php
/**
 * Helper para gerenciar horários de funcionamento
 * Funções auxiliares para obter horários de funcionamento
 */

/**
 * Obter horários de funcionamento para um dia específico
 * @param int $day_of_week (0=Domingo, 1=Segunda, ..., 6=Sábado)
 * @return array ['hours_start', 'hours_end', 'is_closed']
 */
function get_business_hours($day_of_week = null) {
    if ($day_of_week === null) {
        $day_of_week = date('w'); // Dia da semana atual
    }
    
    $result = query("SELECT * FROM business_hours WHERE day_of_week=" . (int)$day_of_week);
    $hours = fetch_assoc($result);
    
    if (!$hours) {
        return [
            'hours_start' => '08:00',
            'hours_end' => '22:00',
            'is_closed' => 0
        ];
    }
    
    return $hours;
}

/**
 * Obter todos os horários de funcionamento
 * @return array Horários indexados por dia da semana
 */
function get_all_business_hours() {
    $result = query("SELECT * FROM business_hours ORDER BY day_of_week");
    $hours = [];
    
    while ($row = fetch_assoc($result)) {
        $hours[$row['day_of_week']] = $row;
    }
    
    // Preencher dias faltantes com valores padrão
    for ($day = 0; $day < 7; $day++) {
        if (!isset($hours[$day])) {
            $hours[$day] = [
                'day_of_week' => $day,
                'hours_start' => '08:00',
                'hours_end' => '22:00',
                'is_closed' => 0
            ];
        }
    }
    
    return $hours;
}

/**
 * Verificar se está aberto agora
 * @return bool true se aberto, false se fechado
 */
function is_open_now() {
    $today_hours = get_business_hours();
    
    if ($today_hours['is_closed']) {
        return false;
    }
    
    $current_time = date('H:i');
    $start = $today_hours['hours_start'];
    $end = $today_hours['hours_end'];
    
    // Se o horário de fechamento é antes do de abertura (ex: 22:00 < 08:00),
    // significa que funciona até a madrugada
    if ($end < $start) {
        return $current_time >= $start || $current_time < $end;
    }
    
    return $current_time >= $start && $current_time < $end;
}

/**
 * Obter status de funcionamento formatado
 * @return string Status formatado (ex: "Aberto até 22:00" ou "Fechado")
 */
function get_status_text() {
    $today_hours = get_business_hours();
    
    if ($today_hours['is_closed']) {
        return 'Fechado hoje';
    }
    
    $current_time = strtotime(date('H:i'));
    $start_time = strtotime($today_hours['hours_start']);
    $end_time = strtotime($today_hours['hours_end']);
    
    if ($current_time < $start_time) {
        return 'Abre às ' . $today_hours['hours_start'];
    }
    
    if ($current_time >= $end_time) {
        return 'Fechado';
    }
    
    return 'Aberto até ' . $today_hours['hours_end'];
}

/**
 * Obter dia da semana em português
 * @param int $day_of_week (0=Domingo, 1=Segunda, ..., 6=Sábado)
 * @return string Nome do dia em português
 */
function get_day_name($day_of_week) {
    $days = [
        0 => 'Domingo',
        1 => 'Segunda-feira',
        2 => 'Terça-feira',
        3 => 'Quarta-feira',
        4 => 'Quinta-feira',
        5 => 'Sexta-feira',
        6 => 'Sábado'
    ];
    
    return $days[$day_of_week] ?? 'Desconhecido';
}
?>
