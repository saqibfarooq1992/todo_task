

 <div class="row">
    <div class="col-md-4 mx-auto shadow bg-white px-3 pt-3">
        <h4 class="text-center confirmation"> Confirmation Email For Password Reset </h4>
        <p class="text-dark message">we have received a request to reset your password if you didnot make this request ,just ignore this email Otherwise you can reset your using the following code.</p>
        <p class="message">You have received below enquiry from {{ucfirst($name)}}</p>
        <p class="text-dark message">Your reset password code: <strong>{{$random}}<strong></p>
        <p class="text-center text-success url">Regards: Techozone </p>
    </div>
</div>
<style>
img{
    display: block;
    margin: auto;
}
.confirmation{
    color: brown;
    text-align: center
}
.url{
    color: green;
    text-align: center;
}
.message{
    text-align: center;
}
</style>
