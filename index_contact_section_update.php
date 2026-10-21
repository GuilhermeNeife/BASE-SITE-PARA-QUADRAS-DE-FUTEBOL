<?php
/**
 * SEÇÃO DE CONTATO ATUALIZADA - Para substituir a seção de contato em index.php
 * Usa os novos horários de funcionamento por dia da semana
 */

// Obter todos os horários de funcionamento
$all_business_hours = get_all_business_hours();

// Mapeamento de dias
$days_map = [
    0 => 'Domingo',
    1 => 'Segunda',
    2 => 'Terça',
    3 => 'Quarta',
    4 => 'Quinta',
    5 => 'Sexta',
    6 => 'Sábado'
];
?>

<!-- CONTATO -->
<section id="contato">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div class="section-tag" style="color: var(--contact-accent);">📞 ENTRE EM CONTATO</div>
        <h2 class="section-title" style="color: var(--contact-text);">Informações de <em style="color: var(--contact-accent);">Contato</em></h2>
    </div>
    
    <div class="contact-info">
        <div class="contact-item">
            <div class="contact-item-title" style="color: var(--contact-accent);">📍 Endereço</div>
            <div class="contact-item-content" style="color: var(--contact-text);"><?php echo htmlspecialchars($contact['address']); ?></div>
        </div>
        
        <div class="contact-item">
            <div class="contact-item-title" style="color: var(--contact-accent);">📞 Telefones</div>
            <div class="contact-item-content" style="color: var(--contact-text);">
                <?php echo htmlspecialchars($contact['phone1']); ?><br>
                <?php if ($contact['phone2']): echo htmlspecialchars($contact['phone2']); endif; ?>
            </div>
        </div>
        
        <div class="contact-item">
            <div class="contact-item-title" style="color: var(--contact-accent);">📧 Email</div>
            <div class="contact-item-content" style="color: var(--contact-text);"><?php echo htmlspecialchars($contact['email']); ?></div>
        </div>
        
        <div class="contact-item">
            <div class="contact-item-title" style="color: var(--contact-accent);">🕐 Horários</div>
            <div class="contact-item-content" style="color: var(--contact-text); font-size: 0.85rem;">
                <?php foreach ($all_business_hours as $day => $hours): ?>
                    <div style="margin-bottom: 8px; display: flex; justify-content: space-between; gap: 10px;">
                        <span style="font-weight: 500; min-width: 80px;"><?php echo $days_map[$day]; ?></span>
                        <span style="opacity: 0.8;">
                            <?php if ($hours['is_closed']): ?>
                                <em>Fechado</em>
                            <?php else: ?>
                                <?php echo htmlspecialchars($hours['hours_start']); ?> - <?php echo htmlspecialchars($hours['hours_end']); ?>
                            <?php endif; ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
