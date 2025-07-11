@extends('cms.layouts.master')

@section('title', $errorTitle ?? 'Error | Pet Grooming CMS')

@section('content')
    
<div class="page-inner">
    <div class="error-container">
        <!-- Simple Background Pattern -->
        <div class="background-pattern"></div>

        <!-- Error Icon -->
        <div class="error-icon">
            @switch($errorCode ?? 500)
                @case(404)
                    <i class="fas fa-search"></i>
                    @break
                @case(403)
                    <i class="fas fa-lock"></i>
                    @break
                @case(401)
                    <i class="fas fa-user-slash"></i>
                    @break
                @case(419)
                    <i class="fas fa-clock"></i>
                    @break
                @case(429)
                    <i class="fas fa-tachometer-alt"></i>
                    @break
                @case(500)
                    <i class="fas fa-exclamation-triangle"></i>
                    @break
                @case(503)
                    <i class="fas fa-tools"></i>
                    @break
                @default
                    <i class="fas fa-exclamation-circle"></i>
            @endswitch
        </div>

        <!-- Error Code -->
        <div class="error-code">{{ $errorCode ?? 500 }}</div>

        <!-- Error Title -->
        <h1 class="error-title">
            @switch($errorCode ?? 500)
                @case(404)
                    Page Not Found
                    @break
                @case(403)
                    Access Denied
                    @break
                @case(401)
                    Unauthorized
                    @break
                @case(419)
                    Session Expired
                    @break
                @case(429)
                    Too Many Requests
                    @break
                @case(500)
                    Server Error
                    @break
                @case(503)
                    Service Unavailable
                    @break
                @default
                    {{ $errorTitle ?? 'Something Went Wrong' }}
            @endswitch
        </h1>

        <!-- Error Message -->
        <p class="error-message">
            @if(isset($errorMessage))
                {{ $errorMessage }}
            @else
                @switch($errorCode ?? 500)
                    @case(404)
                        The page you're looking for doesn't exist or has been moved.
                        @break
                    @case(403)
                        You don't have permission to access this resource.
                        @break
                    @case(401)
                        Please log in to access this page.
                        @break
                    @case(419)
                        Your session has expired. Please refresh and try again.
                        @break
                    @case(429)
                        You're making too many requests. Please wait a moment.
                        @break
                    @case(500)
                        Something went wrong on our servers. We're working to fix it.
                        @break
                    @case(503)
                        We're temporarily unavailable for maintenance.
                        @break
                    @default
                        An unexpected error occurred. Please try again later.
                @endswitch
            @endif
        </p>

        <!-- Action Buttons -->
        <div class="error-actions">
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Go Back
            </a>
            <a href="{{ route('dashboard.index') }}" class="btn btn-primary">
                <i class="fas fa-home me-2"></i>Dashboard
            </a>
        </div>

        <!-- Auto Redirect -->
        @if(isset($autoRedirect) && $autoRedirect)
        <div class="auto-redirect">
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            <p>Redirecting in <span id="countdown">5</span> seconds...</p>
        </div>
        @endif
    </div>
</div>

@endsection

@push('styles')
<style>
.error-container {
    text-align: center;
    padding: 6rem 2rem;
    min-height: 70vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    margin: 2rem 0;
    position: relative;
    overflow: hidden;
}

.background-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
    pointer-events: none;
}

.error-icon {
    font-size: 6rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 2rem;
    text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.error-code {
    font-size: 8rem;
    font-weight: 900;
    color: white;
    margin-bottom: 1rem;
    text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    background: linear-gradient(45deg, #ffffff, #f8f9fa);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.error-title {
    font-size: 2.5rem;
    font-weight: 600;
    color: white;
    margin-bottom: 1.5rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.error-message {
    font-size: 1.2rem;
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.6;
    margin-bottom: 3rem;
    max-width: 500px;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.error-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: center;
}

.error-actions .btn {
    padding: 0.75rem 2rem;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 140px;
}

.error-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.auto-redirect {
    margin-top: 2rem;
    max-width: 300px;
    margin-left: auto;
    margin-right: auto;
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #4ecdc4, #45b7d1);
    width: 0%;
    transition: width 0.1s ease;
    border-radius: 3px;
}

.auto-redirect p {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
    margin: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .error-container {
        padding: 4rem 1rem;
        margin: 1rem 0;
    }
    
    .error-icon {
        font-size: 4rem;
    }
    
    .error-code {
        font-size: 5rem;
    }
    
    .error-title {
        font-size: 2rem;
    }
    
    .error-message {
        font-size: 1.1rem;
    }
    
    .error-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .error-actions .btn {
        width: 100%;
        max-width: 250px;
    }
}

@media (max-width: 480px) {
    .error-container {
        padding: 3rem 1rem;
    }
    
    .error-icon {
        font-size: 3rem;
    }
    
    .error-code {
        font-size: 4rem;
    }
    
    .error-title {
        font-size: 1.5rem;
    }
    
    .error-message {
        font-size: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Auto redirect functionality
    @if(isset($autoRedirect) && $autoRedirect)
    let countdown = 5;
    const countdownElement = $('#countdown');
    const progressFill = $('#progressFill');
    const totalTime = 5000; // 5 seconds
    let elapsed = 0;
    
    const redirectTimer = setInterval(function() {
        elapsed += 100;
        const progress = (elapsed / totalTime) * 100;
        
        progressFill.css('width', progress + '%');
        
        if (elapsed >= totalTime) {
            clearInterval(redirectTimer);
            window.location.href = '{{ $redirectUrl ?? route("dashboard.index") }}';
        }
    }, 100);
    
    const countdownTimer = setInterval(function() {
        countdown--;
        countdownElement.text(countdown);
        
        if (countdown <= 0) {
            clearInterval(countdownTimer);
        }
    }, 1000);
    @endif
});
</script>
@endpush 