$(document).ready(function(){   

    $('#bar').click(function(){        
        $(this).toggleClass('open');
        $('#page-content-wrapper ,#sidebar-wrapper').toggleClass('toggled' );
        var lebarNavbarWrap = $(".navbar").width()
        $(".topBar-input").css("width",lebarNavbar-500)
    });


    $(".btn-navbar-search").click(function(){
        $(this).css("display","none");
        $(".navbar-search-overlap").addClass("show");
    })

    $(".btn-custom-navbar").click(function(){
        $(".navbar-search-overlap").removeClass("show");    
        $(".btn-navbar-search").css("display","block")
    })

    var lebarNavbar = $(".navbar").width()

    $(".topBar-input").css("width",lebarNavbar-500)
   

    $(window).resize(function(){
        var lebarNavbarResize = $(".navbar").width()
        $(".topBar-input").css("width",lebarNavbarResize-500)
    })

    
    $(".nav-sub").click(function(){            
       var value = $(this).attr("data-name");        
       dinamisValue(value)              
    })
    

    let subItem = '';

    function dinamisValue(value){
        subItem = value;        
        $(".nav-subItems-"+subItem).css("left","0px")
        $(".nav-items").css("left","-20rem")        

        $(".sub-item-show").click(function(){  
            console.log("hai")
            $(".nav-subItems-"+value).css("left","20rem")
            $(".nav-items").css("left","0px")
        })
    }

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    
    $(".nav-some").click(function(){
        var value = $(this).attr("data-name");
        dinamisSomeValue(value);
    })      

    function dinamisSomeValue(value){        
        $(".nav-someItems-"+value).css("left","0px");
        $(".nav-subItems-"+subItem).css("left","-20rem")

        $(".some-items").click(function(){
            $(".nav-someItems-"+value).css("left","-20rem");
            $(".nav-subItems-"+subItem).css("left","0");
        })
    }

})  