<?php
/**
 * Email Styles
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-styles.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 4.0.0
 */
if(!defined('ABSPATH')){
	exit;
}
?>
p {
  font-family: "neuzeit-grotesk", 'BlinkMacSystemFont', -apple-system, 'Roboto', 'Lucida Sans';
  font-style: normal;
  font-size: 1.1em !important;
  font-weight: 400;
  margin: 1em 0;
  padding: 0;
}

table {
  border-collapse: collapse;
}

h1,
h2,
h3,
h4,
h5,
h6 {
  font-family: "neuzeit-grotesk", 'BlinkMacSystemFont', -apple-system, 'Roboto', 'Lucida Sans';
  font-style: normal;
  font-weight: 400;
  display: block;
  margin: 0;
  padding: 0;
}

img,
a img {
  border: 0;
  height: auto;
  outline: none;
  text-decoration: none;
}

body,
#bodyTable,
#bodyCell {
  height: 100%;
  margin: 0;
  padding: 0;
  width: 100%;
}

#outlook a {
  padding: 0;
}

img {
  -ms-interpolation-mode: bicubic;
}

table {
  mso-table-lspace: 0;
  mso-table-rspace: 0;
}

.ReadMsgBody {
  width: 100%;
}

.ExternalClass {
  width: 100%;
}

p,
a,
li,
td,
blockquote {
  mso-line-height-rule: exactly;
}

a[href^=tel],
a[href^=sms] {
  color: inherit;
  cursor: default;
  text-decoration: none;
}

p,
a,
li,
td,
body,
table,
blockquote {
  -ms-text-size-adjust: 100%;
  -webkit-text-size-adjust: 100%;
}

.ExternalClass,
.ExternalClass p,
.ExternalClass td,
.ExternalClass div,
.ExternalClass span,
.ExternalClass font {
  line-height: 100%;
}

a[x-apple-data-detectors] {
  color: inherit !important;
  text-decoration: none !important;
  font-size: inherit !important;
  font-family: inherit !important;
  font-weight: inherit !important;
  line-height: inherit !important;
}

#bodyCell {
  padding: 9px;
}

.templateImage {
  height: auto;
  max-width: 564px;
}

.templateContainer {
  max-width: 600px !important;
  border: 0;
  opacity: 0.9;
}

#templatePreheader {
  padding-right: 9px;
  padding-left: 9px;
}

#templatePreheader .columnContainer td {
  padding: 0 9px;
}

#footerContent {
  padding-top: 27px;
  padding-bottom: 18px;
}

#templateHeader,
#templateBody,
#templateFooter {
  padding-right: 18px;
  padding-left: 18px;
}

.button {
  font-size: 1em !important;
  background: #919692 !important;
  color: #ffffff !important;
  text-decoration: none !important;
  padding: 15px 30px !important;
}

.button:hover {
  box-shadow: inset 0 0 0 99999px rgba(0, 0, 0, .1) !important;
}

.utilityLink {
  margin: 0 10px;
}

.utilityLink img {
  height: 32px;
  width: 32px;
}

body,
#bodyTable {
  background-color: #fafafa;
}

h1 {
  color: #343434;
  font-family: "neuzeit-grotesk", 'BlinkMacSystemFont', -apple-system, 'Roboto', 'Lucida Sans';
  font-size: 34px;
  font-style: normal;
  font-weight: normal;
  line-height: 150%;
  letter-spacing: normal;
  text-align: left;
}

h2 {
  color: #343434;
  font-family: "neuzeit-grotesk", 'BlinkMacSystemFont', -apple-system, 'Roboto', 'Lucida Sans';
  font-size: 34px;
  font-style: normal;
  font-weight: normal;
  line-height: 150%;
  letter-spacing: normal;
  text-align: left;
  opacity: .9;
}

h3 {
  color: #343434;
  font-family: "neuzeit-grotesk", 'BlinkMacSystemFont', -apple-system, 'Roboto', 'Lucida Sans';
  font-size: 22px;
  font-style: normal;
  font-weight: bold;
  line-height: 150%;
  letter-spacing: normal;
  text-align: left;
  opacity: .8;
}

h4 {
  color: #343434;
  font-family: "neuzeit-grotesk", 'BlinkMacSystemFont', -apple-system, 'Roboto', 'Lucida Sans';
  font-size: 20px;
  font-style: italic;
  font-weight: normal;
  line-height: 150%;
  letter-spacing: normal;
  text-align: left;
  opacity: .7;
}

#templatePreheader {
  background-color: #FAFAFA;
  background-image: none;
  background-repeat: no-repeat;
  background-position: center;
  background-size: cover;
  border-top: 0;
  border-bottom: 0;
  padding-top: 9px;
  padding-bottom: 9px;
}

#templatePreheader,
#templatePreheader p {
  color: #656565;
  font-family: "neuzeit-grotesk", 'BlinkMacSystemFont', -apple-system, 'Roboto', 'Lucida Sans';
  font-size: 12px;
  line-height: 150%;
  text-align: left;
}

#templatePreheader a,
#templatePreheader p a {
  color: #656565;
  font-weight: normal;
  text-decoration: underline;
}

#templateHeader {
  background-color: #f8f8f8;
  background-image: none;
  background-repeat: no-repeat;
  background-position: center;
  background-size: cover;
  border-top: 0;
  border-bottom: 0;
  padding-top: 18px;
  padding-bottom: 0;
}

#templateHeader,
#templateHeader p,
#templateFooter p {
  color: #606060;
  font-family: "neuzeit-grotesk", 'BlinkMacSystemFont', -apple-system, 'Roboto', 'Lucida Sans';
  font-size: 16px;
  line-height: 150%;
  text-align: left;
}

#templateHeader a,
#templateHeader p a {
  color: #237A91;
  font-weight: normal;
  text-decoration: underline;
}

#templateBody {
  background-color: #f8f8f8;
  background-image: none;
  background-repeat: no-repeat;
  background-position: center;
  background-size: cover;
  border-top: 0;
  border-bottom: 2px solid #EAEAEA;
  padding-top: 0;
  padding-bottom: 9px;
}

#templateBody,
#templateBody p {
  color: #606060;
  font-family: "neuzeit-grotesk", 'BlinkMacSystemFont', -apple-system, 'Roboto', 'Lucida Sans';
  font-size: 16px;
  line-height: 150%;
  text-align: left;
}

#templateBody a,
#templateBody p a {
  color: #919692;
  font-weight: normal;
  text-decoration: underline;
}

#templateBody h1,
#templateBody h2 {
    text-align: center;
    color: #343434;
}

#templateFooter {
  background-color: #f8f8f8;
  background-image: none;
  background-repeat: no-repeat;
  background-position: center;
  background-size: cover;
  border-top: 0;
  border-bottom: 0;
  padding-top: 36px;
  padding-bottom: 9px;
}

#templateFooter h1,
#templateFooter h2,
#templateFooter h3,
#templateFooter p {
    text-align: center;
}

#socialBar {
  background-color: #f8f8f8;
  border: 0;
  padding: 18px;
}

#socialBar,
#socialBar p {
  color: #FFFFFF;
  font-family: "neuzeit-grotesk", 'BlinkMacSystemFont', -apple-system, 'Roboto', 'Lucida Sans';
  font-size: 12px;
  line-height: 150%;
  text-align: center;
}

#socialBar a,
#socialBar p a {
  color: #FFFFFF;
  font-weight: normal;
  text-decoration: underline;
}

#footerContent,
#footerContent p {
  color: #656565;
  font-family: "neuzeit-grotesk", 'BlinkMacSystemFont', -apple-system, 'Roboto', 'Lucida Sans';
  font-size: 12px;
  line-height: 150%;
  text-align: center;
}

#footerContent a,
#footerContent p a {
  color: #656565;
  font-weight: normal;
  text-decoration: underline;
}

#utilityBar {
  background-color: #f8f8f8;
  border: 0;
  padding-top: 9px;
  padding-bottom: 9px;
}

#utilityBar,
#utilityBar p {
  color: #656565;
  font-family: "neuzeit-grotesk", 'BlinkMacSystemFont', -apple-system, 'Roboto', 'Lucida Sans';
  font-size: 12px;
  line-height: 150%;
  text-align: center;
}

#utilityBar a,
#utilityBar p a {
  color: #656565;
  font-weight: normal;
  text-decoration: underline;
}

@media only screen and (max-width: 480px) {
  body,
  table,
  td,
  p,
  a,
  li,
  blockquote {
    -webkit-text-size-adjust: none !important;
  }
}

@media only screen and (max-width: 480px) {
  body {
    width: 100% !important;
    min-width: 100% !important;
  }
}

@media only screen and (max-width: 480px) {
  .templateImage {
    width: 100% !important;
  }
}

@media only screen and (max-width: 480px) {
  .columnContainer {
    max-width: 100% !important;
    width: 100% !important;
  }
}

@media only screen and (max-width: 480px) {
  .mobileHide {
    display: none;
  }
}

@media only screen and (max-width: 480px) {
  .utilityLink {
    display: block;
    padding: 9px 0;
  }
}

@media only screen and (max-width: 480px) {
  h1 {
    font-size: 22px !important;
    line-height: 175% !important;
  }
}

@media only screen and (max-width: 480px) {
  h2 {
    font-size: 20px !important;
    line-height: 175% !important;
  }
}

@media only screen and (max-width: 480px) {
  h3 {
    font-size: 18px !important;
    line-height: 175% !important;
  }
}

@media only screen and (max-width: 480px) {
  h4 {
    font-size: 16px !important;
    line-height: 175% !important;
  }
}

@media only screen and (max-width: 480px) {
  #templatePreheader {
    display: block !important;
  }
}

@media only screen and (max-width: 480px) {
  #templatePreheader,
  #templatePreheader p {
    font-size: 14px !important;
    line-height: 150% !important;
  }
}

@media only screen and (max-width: 480px) {
  #templateHeader,
  #templateHeader p {
    font-size: 16px !important;
    line-height: 150% !important;
  }
}

@media only screen and (max-width: 480px) {
  #templateBody,
  #templateBody p {
    font-size: 16px !important;
    line-height: 150% !important;
  }
}

@media only screen and (max-width: 480px) {
  #templateFooter,
  #templateFooter p {
    font-size: 14px !important;
    line-height: 150% !important;
  }
}

@media only screen and (max-width: 480px) {
  #socialBar,
  #socialBar p {
    font-size: 14px !important;
    line-height: 150% !important;
  }

}

@media only screen and (max-width: 480px) {
  #utilityBar,
  #utilityBar p {
    font-size: 14px !important;
    line-height: 150% !important;
  }
}

.spacer.10 {
  height: 10px;
}

.spacer.20px {
  height: 20px;
}

.spacer.30px {
  height: 30px;
}

.spacer.40px {
  height: 40px;
}

.spacer.50px {
  height: 50px;
}
<?php
