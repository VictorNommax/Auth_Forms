const d = document;
const xhr = new XMLHttpRequest();


            window.onload = (event) => {

                if(d.body.contains(d.getElementById("main_form"))){
                    d.getElementById("login_btn").addEventListener("click", login);
                    d.getElementById("register_prepare").addEventListener("click", open_register);
                    d.getElementById('date').valueAsDate = new Date();
                    d.getElementById('date').max = new Date().toISOString().split("T")[0];
                }else{
                    d.getElementById("link").classList.remove('hidden');
                    check_inst(d.getElementById('inst_name_span').innerText);
                }
                d.getElementById("logout_btn").addEventListener("click", logout);

                //prevention of msg repetition
                if(d.cookie = 'message=new'){
                    d.cookie = 'message=observed';
                }
            }       
            
             function check_inst(inst_url){
                //console.log(inst_url);
                   xhr.open("GET", "app/inst_parse.php?from_url="+inst_url);
                   xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                   xhr.onreadystatechange = () => {
                    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                        console.log(xhr.response);
                        d.getElementById('inst_name_span').innerHTML = xhr.response;
                    }
                   };
                   xhr.send();
             }

            function login(){
                let params = "login="+d.getElementById("login").value+"&pass="+d.getElementById("pass").value;
                xhr.open("GET", "?"+params);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = () => {
                    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                        const response = JSON.parse(xhr.response);
                        if(response.err == null){
                            render_profile(response.user);
                            d.getElementById("message_box").innerHTML += "<div class='message'><span>You are successfully logged!</span></div>";
                        }else{
                            const log = d.getElementById('error_log');
                            log.innerHTML = '';
                            log.innerHTML += '<span>'+response.err+'</span><br>';
                        }
                    }
                };
                xhr.send();
            }

            function open_register(){
                d.getElementById('error_log').innerHTML = '';
                d.getElementById("header_txt").innerHTML = "Registration Form:";
                d.title = "Registry";

                d.getElementById("photo_field").classList.remove("hidden");
                d.getElementById("repeat_field").classList.remove("hidden");
                d.getElementById("name").classList.remove("hidden");
                d.getElementById("date").classList.remove("hidden");
                d.getElementById("inst_url").classList.remove("hidden");
                d.getElementById("or_block").classList.remove("hidden");
                d.getElementById("login_btn").classList.add("hidden");
                d.getElementById("login_btn").type = "button";

                d.getElementById("register_btn").classList.remove("hidden");
                d.getElementById("register_prepare").classList.add("hidden");

                d.getElementById("register_btn").type = "submit";
                
                const form = document.forms[0];
            
                form.addEventListener(
                "submit",
                (event) => {
                    if(pass_match_check()){
                        const formData = new FormData(form);

                        xhr.open("POST", "app/register.php", true);
                        xhr.onload = () => {
                        
                            if(xhr.response == 'GOOD'){
                                //cookie for message flood prevention
                                document.cookie = "message=new";
                                window.location.href = window.location.href + "?login=" +
                                d.getElementById("login").value + "&msg=You have registered!";
                            }else{
                                display_errors(JSON.parse(xhr.response));
                            }
                    // error_log.innerHTML =
                        /*    xhr.status === 200
                            ? "Uploaded!"
                            : `Error ${xhr.status} occurred when trying to upload your file.<br />`;*/
                        };

                        xhr.send(formData);
                    }
                    
                    event.preventDefault();
                },
                false
                );
            }

            function logout(){
                xhr.open("GET", "?logout=true");
                xhr.onreadystatechange = () => {
                    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                        location.reload();
                    }
                };
                xhr.send();
            }

            function pass_match_check(){
                d.getElementById('error_log').innerHTML = '';
                let pass_rep = d.getElementById("repeat_field");
                let pass = d.getElementById("pass");

                if(pass_rep.value != pass.value){
                    if(!pass_rep.classList.contains('mistake'))
                        pass_rep.classList.add('mistake');
                        d.getElementById('error_log').innerHTML += '<span>Passwords are not the same.</span><br>';
                        return false;
                }else{
                    if(pass_rep.classList.contains('mistake'))
                        pass_rep.classList.remove('mistake');
                    return true;
                }
            }

            function render_profile(user){
                
                let profile = '<div class="avatar column" ';
                if(user.photo == null){
                    profile+='><div class="inst_name"><span>Name in Instagram: <span id="inst_name_span" class="straight_name">'+check_inst(user.inst_url)+'</span></span></div>';
                }else{
                    profile+='style="background-image:url(\'photos/'+user.photo+'\')">';
                }
                profile+='<div class="user_info column">';
                profile+='<div class="user_name row"><span>'+user.name+'</span></div>';
                profile+='<div class="user_data row"><span>Login: <span class="straight">'+user.login+'</span></span>';
                profile+='<span>Birth date: <span class="straight">'+user.birth_date+'</span></span></div>';
                profile+="</div></div>"
                d.getElementById('main_container').innerHTML = profile;
              
                d.getElementById("link").classList.remove('hidden');
                d.getElementById("logout_btn").classList.remove('hidden');
            }

            function display_errors(errors){
                const log = d.getElementById('error_log');
                log.innerHTML = '';
                let errors_arr = [];
                //firstly we need to obtain array 
                for(var i in errors)
                    errors_arr.push([i, errors[i]]);
                //now we can use forEach
                errors_arr.forEach(element => {
                    if(element[1] != 'OK'){
                        if(!d.getElementById(element[0]).classList.contains('mistake'))
                            d.getElementById(element[0]).classList.add('mistake');
                        log.innerHTML += '<span>'+element[1]+'</span><br>';
                    }else{
                        if(d.getElementById(element[0]).classList.contains('mistake'))
                            d.getElementById(element[0]).classList.remove('mistake');
                    }
                });
            }

