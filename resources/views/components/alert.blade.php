<?php
$body = "";
if (isset($errors) && $errors->any() || session()->has('message') || Session::get('alert-danger')){
    $style = "";

    if($errors_directly = Session::get('alert-danger')){
        $style = "error";
        foreach($errors_directly as $error_directly){
            $body.=$error_directly."<br />";
        }
    }

    if ($errors->any()){
        $style = "error";
        foreach($errors->all() as $error){
            $body.=$error."<br />";
        }
    }


    if(session()->has('message')){
        $style = "success";
        $body=session()->get('message');
    }


    $colorName="";
    if($style=="success"){
        $colorName="green";
    }
    if($style=="error"){
        $colorName="red";
    }

}
?>
@if($body)
<div x-data="{ open: true }">
    <div class="bg-{{$colorName}}-100 mb-5 border border-{{$colorName}}-400 text-{{$colorName}}-700 px-4 py-3 rounded relative" role="alert"  x-show="open" >
        <div class="blocḱ w-full">
            <strong class="font-bold">{{__('laravia.heart::common.alert'.ucfirst($style))}}</strong>
        </div>
        <span class="block sm:inline">{!!$body!!}</span>
    </div>
</div>
@endif
