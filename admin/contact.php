<?php 
require_once 'header.php'; 
$contact = fetch_assoc(query("SELECT * FROM contact_info LIMIT 1"));
?>

<div class="card">
    <div class="card-title">📞 Informações de Contato</div>
    <form action="process.php" method="POST">
        <input type="hidden" name="action" value="update_contact">
        
        <div class="form-group">
            <label>Endereço Completo</label>
            <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($contact['address']); ?>">
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label>Telefone 1 (Principal)</label>
                <input type="text" name="phone1" class="form-control" value="<?php echo htmlspecialchars($contact['phone1']); ?>">
            </div>
            <div class="form-group">
                <label>Telefone 2 (Opcional)</label>
                <input type="text" name="phone2" class="form-control" value="<?php echo htmlspecialchars($contact['phone2']); ?>">
            </div>
        </div>

        <div class="form-group">
            <label>E-mail de Contato</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($contact['email']); ?>">
        </div>
        
        <button type="submit" class="btn btn-primary">💾 Salvar Contato</button>
    </form>
</div>

<?php require_once 'footer.php'; ?>
