            </div> <!-- .content-body -->
        </main>
    </div> <!-- .admin-wrapper -->

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const openBtn = document.getElementById('open-sidebar');
        const closeBtn = document.getElementById('close-sidebar');

        function toggleMenu() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        }

        openBtn.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);
        if(closeBtn) closeBtn.addEventListener('click', toggleMenu);

        // Ajustar exibição do botão fechar no mobile
        function checkMobile() {
            if (window.innerWidth <= 992) {
                if(closeBtn) closeBtn.style.display = 'block';
            } else {
                if(closeBtn) closeBtn.style.display = 'none';
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
        window.addEventListener('resize', checkMobile);
        checkMobile();
    </script>
</body>
</html>
