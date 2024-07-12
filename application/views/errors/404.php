<?php $this->load->view('frontend/layout/header'); ?>

    <div id="notfound">
        <div class="notfound">
            <div class="notfound-404">
                <h1>404</h1>
            </div>
            <h2>We are sorry, Page not found!</h2>
            <p>The page you are looking for might have been removed had its name changed or is temporarily
                unavailable.</p>
            <a href="<?php echo base_url() ?>">Back To Homepage</a>
        </div>
    </div>

<?php $this->load->view('frontend/layout/footer'); ?>