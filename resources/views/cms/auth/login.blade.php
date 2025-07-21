<!DOCTYPE html>
<html lang="en">
  <head>
  	<title>PupSplash | Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="{{ asset('assets/auth/css/style.css') }}">

	</head>
	<body>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					{{-- <h2 class="heading-section">Login</h2> --}}
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-7 col-lg-5">
					<div class="login-wrap p-4 p-md-5">
                        <div class="icon d-flex align-items-center justify-content-center">
                            <span class="fa fa-user-o"></span>
                        </div>
                        <h3 class="text-center mb-4">Sign In</h3>
                        <form class="login-form" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control rounded-left @error('email') is-invalid @enderror" name="email" placeholder="Email" value="{{ old('email') }}">
                                @error('email')
                                    <small class="error-message">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                            <input type="password" class="form-control rounded-left @error('password') is-invalid @enderror" name="password" placeholder="Password">
                            @error('password')
                                <small class="error-message">{{ $message }}</small>
                            @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="form-control btn btn-primary rounded submit px-3">Login</button>
                            </div>
                            <div class="form-group d-md-flex">
                                <div class="w-50">
                                    <label class="checkbox-wrap checkbox-primary">Remember Me
                                        <input type="checkbox" checked>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="w-50 text-md-right">
                                    <a href="#">Forgot Password?</a>
                                </div>
                            </div>
                        </form>
                        

	            </div>
                                        
                    <div class="text-md-center pt-4">Don't have an account? <a href="{{ route('register')}} ">Register</a></div>

				</div>
			</div>
		</div>
	</section>

    <script src="{{ asset('assets/auth/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/auth/js/popper.js') }}"></script>
    <script src="{{ asset('assets/auth/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/auth/js/main.js') }}"></script>
    <script src="{{ asset('assets/auth/js/script.js') }}"></script>    

	</body>
</html>

