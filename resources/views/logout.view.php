<?php
if (isPost()) {
    verifyCsrf();
    logoutUser();
    flash('success', 'Je bent afgemeld.');
}

redirect('?page=login');
