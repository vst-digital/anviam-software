<div class="infobox">
<h2>Download File</h2>

This will apply the resized image to the background of every page that referances the stylesheet. if for example you want it to only be on one page kinda like twitter has it only on their landing page and not all the others. 
 <br>

<br>

<a href="{{$filepath}}" class="download-btn" download>Click here to Download File</a><br>
  <br>

Thank You<br>
  <br>
</div>
<style type="text/css">
	body
    {
    width: 100%;
    height: auto;
    background-image: url("/images/c1.jpg");
    background-size: cover;
    background-attachment:fixed;
    margin: 0;
    padding: 0;
    }

/* ========== DEMO STYLES ========== */
@font-face {
  font-family: 'Open Sans';
  font-style: normal;
  font-weight: 400;
  src: local('Open Sans'), local('OpenSans'), url(http://themes.googleusercontent.com/static/fonts/opensans/v6/cJZKeOuBrn4kERxqtaUH3T8E0i7KZn-EPnyo3HZu7kw.woff) format('woff');
}

/* ===== Demo Styles ===== */
.infobox{
  font-family: 'Open Sans';
  font-weight: 400;
  font-size: 16px;
  color: #323232;
  width: 650px;
  height: auto;
  margin: 10% auto;
  padding: 10px;
  background-color: #c5c5c5;
  border: 1px solid #f5f5f5;
  border-radius: 15px;
  -webkit-box-shadow:  0px 0px 10px 0px #f5f5f5;
  box-shadow:  0px 0px 10px 0px #f5f5f5;
}
.download-btn {
    font-size: 18px;
    padding: 10px 20px;
    background: #000;
    color: #fff;
    border-radius: 22px !important;
    text-decoration: none;
}
</style>