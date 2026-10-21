<?php
session_start();
require_once '../config.php';
require_once '../auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Helper para redirecionar com mensagem
    function done($url, $msg, $type = 'success') {
        $_SESSION['message'] = $msg;
        $_SESSION['message_type'] = $type;
        header("Location: $url");
        exit;
    }

    // CONFIGURAÇÕES
    if ($action === 'update_site_settings') {
        $title = escape($_POST['site_title']);
        $desc = escape($_POST['site_description']);
        $l_text = escape($_POST['logo_text']);
        $l_tag = escape($_POST['logo_tagline']);
        $use_img = isset($_POST['use_logo_image']) ? 1 : 0;
        
        query("UPDATE site_settings SET site_title='$title', site_description='$desc', logo_text='$l_text', logo_tagline='$l_tag', use_logo_image=$use_img WHERE id=1");
        done('index.php', 'Configurações salvas!');
    }

    if ($action === 'upload_logo') {
        $upload = upload_file('logo_image');
        if ($upload['success']) {
            $filename = escape($upload['filename']);
            query("UPDATE site_settings SET logo_image='$filename' WHERE id=1");
            done('index.php', 'Logo atualizada!');
        }
        done('index.php', $upload['message'], 'error');
    }

    // CORES
    if ($action === 'update_nav_colors') {
        $bg = escape($_POST['nav_bg_color']);
        $txt = escape($_POST['nav_text_color']);
        $btn = escape($_POST['nav_button_color']);
        $btxt = escape($_POST['nav_button_text_color']);
        query("UPDATE nav_settings SET nav_bg_color='$bg', nav_text_color='$txt', nav_button_color='$btn', nav_button_text_color='$btxt' WHERE id=1");
        done('colors.php', 'Cores do menu atualizadas!');
    }

    if ($action === 'update_section_style') {
        $name = escape($_POST['section_name']);
        $type = escape($_POST['bg_type']);
        $bg = escape($_POST['bg_color']);
        $txt = escape($_POST['text_color']);
        $acc = escape($_POST['accent_color']);
        $opac = (int)($_POST['overlay_opacity'] ?? 0);
        query("UPDATE section_styles SET bg_type='$type', bg_color='$bg', text_color='$txt', accent_color='$acc', overlay_opacity=$opac WHERE section_name='$name'");
        done('colors.php', 'Estilo da seção atualizado!');
    }

    // HERO
    if ($action === 'update_hero') {
        $badge = escape($_POST['badge']);
        $b_col = escape($_POST['badge_color']);
        $title = escape($_POST['title']);
        $e_txt = escape($_POST['title_emphasis']);
        $e_col = escape($_POST['emphasis_color']);
        $desc = escape($_POST['description']);
        query("UPDATE hero_section SET badge='$badge', badge_color='$b_col', title='$title', title_emphasis='$e_txt', emphasis_color='$e_col', description='$desc' WHERE id=1");
        done('hero.php', 'Hero atualizado!');
    }

    if ($action === 'upload_hero_image') {
        $upload = upload_file('hero_image');
        if ($upload['success']) {
            $filename = escape($upload['filename']);
            query("UPDATE hero_section SET hero_image='$filename' WHERE id=1");
            done('hero.php', 'Imagem do Hero atualizada!');
        }
        done('hero.php', $upload['message'], 'error');
    }



    // ESTATÍSTICAS
    if ($action === 'update_stat') {
        $id = (int)$_POST['stat_id'];
        $num = escape($_POST['stat_number']);
        $lab = escape($_POST['stat_label']);
        query("UPDATE statistics SET stat_number='$num', stat_label='$lab' WHERE id=$id");
        done('stats.php', 'Estatística atualizada!');
    }

    // DEPOIMENTOS
    if ($action === 'update_testimonial') {
        $id = (int)$_POST['test_id'];
        $txt = escape($_POST['text']);
        $auth = escape($_POST['author_name']);
        $role = escape($_POST['author_role']);
        $stars = (int)$_POST['stars'];
        query("UPDATE testimonials SET text='$txt', author_name='$auth', author_role='$role', stars=$stars WHERE id=$id");
        done('testimonials.php', 'Depoimento atualizado!');
    }

    // CONTATO
    if ($action === 'update_contact') {
        $addr = escape($_POST['address']);
        $p1 = escape($_POST['phone1']);
        $p2 = escape($_POST['phone2']);
        $mail = escape($_POST['email']);
        query("UPDATE contact_info SET address='$addr', phone1='$p1', phone2='$p2', email='$email' WHERE id=1");
        done('contact.php', 'Contato atualizado!');
    }

    // AMBIENTES
    if ($action === 'create_environment') {
        $name = escape($_POST['name']);
        $desc = escape($_POST['description']);
        $icon = escape($_POST['icon']);
        $res = query("SELECT MAX(`order`) as max_o FROM environments");
        $row = fetch_assoc($res);
        $next = ($row['max_o'] ?? 0) + 1;
        query("INSERT INTO environments (name, description, icon, `order`) VALUES ('$name', '$desc', '$icon', $next)");
        done('environments.php', 'Ambiente criado!');
    }

    if ($action === 'update_environment') {
        $id = (int)$_POST['env_id'];
        $name = escape($_POST['name']);
        $desc = escape($_POST['description']);
        $icon = escape($_POST['icon']);
        $vis = isset($_POST['visible']) ? 1 : 0;
        query("UPDATE environments SET name='$name', description='$desc', icon='$icon', visible=$vis WHERE id=$id");
        done('environments.php', 'Ambiente atualizado!');
    }

    if ($action === 'delete_environment') {
        $id = (int)$_POST['env_id'];
        // Deletar imagens
        $imgs = fetch_all(query("SELECT image_path FROM environment_images WHERE environment_id=$id"));
        foreach($imgs as $img) delete_file($img['image_path']);
        // Deletar capa
        $env = fetch_assoc(query("SELECT cover_image FROM environments WHERE id=$id"));
        if($env['cover_image']) delete_file($env['cover_image']);
        
        query("DELETE FROM environments WHERE id=$id");
        done('environments.php', 'Ambiente removido!');
    }

    if ($action === 'reorder_environments') {
        foreach($_POST['orders'] as $id => $order) {
            $id = (int)$id; $order = (int)$order;
            query("UPDATE environments SET `order`=$order WHERE id=$id");
        }
        done('environments.php', 'Ordem atualizada!');
    }

    if ($action === 'upload_environment_cover') {
        $id = (int)$_POST['env_id'];
        $upload = upload_file('cover_image');
        if($upload['success']) {
            $fname = escape($upload['filename']);
            query("UPDATE environments SET cover_image='$fname' WHERE id=$id");
            done('environments.php', 'Capa atualizada!');
        }
        done('environments.php', $upload['message'], 'error');
    }

    if ($action === 'upload_environment_images') {
        $id = (int)$_POST['env_id'];
        $files = $_FILES['environment_images'];
        $count = 0;
        for($i=0; $i<count($files['name']); $i++) {
            $_FILES['temp'] = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i]
            ];
            $upload = upload_file('temp');
            if($upload['success']) {
                $fname = escape($upload['filename']);
                query("INSERT INTO environment_images (environment_id, image_path, `order`) VALUES ($id, '$fname', $i)");
                $count++;
            }
        }
        done('environments.php', "$count fotos adicionadas!");
    }

    if ($action === 'delete_environment_image') {
        $id = (int)$_POST['img_id'];
        $img = fetch_assoc(query("SELECT image_path FROM environment_images WHERE id=$id"));
        if($img) delete_file($img['image_path']);
        query("DELETE FROM environment_images WHERE id=$id");
        done('environments.php', 'Foto removida!');
    }

    if ($action === 'update_testimonials_visibility') {
        $show = isset($_POST['show_testimonials']) ? 1 : 0;
        query("UPDATE site_settings SET show_testimonials=$show WHERE id=1");
        done('testimonials.php', 'Visibilidade atualizada!');
    }
}

header('Location: index.php');
exit;
?>
