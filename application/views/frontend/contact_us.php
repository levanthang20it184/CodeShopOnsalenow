<?php $this->load->view('frontend/layout/header'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style type="text/css">
    span.CompareBtn.text-right a i {
        color: #1980ff;
    }
    .address-container {
            display: flex;
            align-items: center;
            font-size: 16px;
            color: #555;
            padding: 20px;
            border-radius: 8px;
        }

        .address-container i {
            margin-right: 10px;
            font-size: 25px; /* Tăng kích thước của biểu tượng */
            color: #CC0000;
            margin-bottom: 25px;
        }

        address {
            font-style: normal;
            line-height: 1.5;
        }

        .address-container span {
            display: block;
        }
</style>


<section class="theme-padding Contact-Page">
    <div class="container position-set">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading headingBox text-left">
                    <h1 class="color-fff"><b>Contact Us</b></h1>
                    <p class="color-fff">Please complete the form below and we will get back to you within 24 hours.
                        Thank you for your interest!</p>
                </div>
            </div>
        </div>
        <div class="Contact-FormBox">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-7">
                    <?php
                    if ($this->session->flashdata('success')) {
                        ?>
                        <div class="alert alert-success col-lg-12 col-md-12 col-sm-12" role="alert">
                            <?php
                            echo $this->session->flashdata('success');
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="FormBox">
                        <form action="<?php echo base_url('contact_us') ?>" method="post">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="email">Name <span style="color:red;">*</span></label>
                                        <input type="text" required class="form-control" name="name"
                                               placeholder="Enter Your Name">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="pwd">Address <span style="color:red;">*</span></label>
                                        <input type="text" required class="form-control" name="address"
                                               placeholder="Enter Your Address">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="email">Theme <span style="color:red;">*</span></label>
                                        <select class="form-control" name="theme" required>
                                            <!-- <option value="1">Theme 1</option>
                                            <option value="2">Theme 2</option>
                                            <option value="3">Theme 3</option>
                                            <option value="4">Theme 4</option> -->
                                            <option selected="" value="">choose...</option>
                                            <option value="Retailer Registration">Retailer Registration</option>
                                            <option value="Advertising">Advertising</option>
                                            <option value="Suggestion Box">Suggestion Box</option>
                                            <option value="Technical Issue">Technical Issue</option>
                                            <option value="Customer Service">Customer Service</option>
                                            <option value="Other Enquiry">Other Enquiry</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="pwd">Location <span style="color:red;">*</span></label>
                                        <select class="form-control" name="location" required>
                                            <option selected="" value="">choose...</option>

                                            <?php foreach ($countries as $key => $value) : ?>

                                                <option value="<?= $value->slug; ?>"><?= $value->name; ?></option>

                                            <?php endforeach; ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="pwd">Company Name</label>
                                        <input type="text" class="form-control" name="company_name"
                                               placeholder="Enter Your Company Name">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="pwd">Telephone <span style="color:red;">*</span></label>
                                        <input type="phone" required class="form-control" name="phone"
                                               placeholder="Enter Your Telephone">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="email">Email <span style="color:red;">*</span></label>
                                        <input type="email" required class="form-control" name="email"
                                               placeholder="Enter Your Email">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="pwd">Information Required <span style="color:red;">*</span></label>
                                        <input type="text" required class="form-control" name="information_required"
                                               placeholder="Enter Your Information Required">
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="pwd">Message </label>
                                        <textarea id="w3review" class="form-control" name="message" rows="4" cols="50"
                                                  placeholder="Enter you message..."></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                                    <div class="g-recaptcha"
                                         data-sitekey="6LdPI64oAAAAANppuXLPQmAiOiPrP7CatG0x5Pog"></div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <button type="submit" name="submit" class="mainbtn" style="float: right;">Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-5">
                    <div class="Contact-Details">
                        <ul>
                            <li>
                                <div class="Contact-DetailsContent">
                                    <iframe style="border: 0;" width="400" height="300"
                                            allowfullscreen="allowfullscreen" frameborder="0" scrolling="no"
                                            marginheight="0" marginwidth="0" 
                                            src="https://maps.google.com/maps?q=53.27352376329135, -6.212791523994646&z=15&ie=UTF8&iwloc=&output=embed">
                                        <a href="https://www.gps.ie/sport-gps/"></a></iframe>
                                </div>
                            </li>
                            <?php echo @$contact_data['description']; ?>
                         <ul>
                         <div class="address-container">
                            <i class="fas fa-map-marker-alt"></i>
                            <address>
                                <span>Onsalenow Ltd, 6 Fern Road, Sandyford Business Park, Dublin, D18 FP98, Ireland</span>
                            </address>
                        </div>                       
                         </ul>                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php $this->load->view('frontend/layout/footer'); ?>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>