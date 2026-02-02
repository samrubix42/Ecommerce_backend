<div>
<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="text-center mb-4">
            <a href="/" class="navbar-brand navbar-brand-autodark">
                <img src="/logo.svg" height="36" alt="Logo">
            </a>
        </div>

        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Login to your account</h2>

                <form wire:submit.prevent="login">
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email"
                               wire:model.defer="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="Enter email">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="form-label">
                            Password
                            <span class="form-label-description">
                                <a href="#">Forgot password?</a>
                            </span>
                        </label>

                        <div class="input-group input-group-flat">
                            <input type="password"
                                   wire:model.defer="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Password">
                        </div>

                        @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="form-check">
                            <input type="checkbox" wire:model="remember" class="form-check-input">
                            <span class="form-check-label">Remember me</span>
                        </label>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">
                            Sign in
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center text-muted mt-3">
            Don’t have an account?
            <a href="#" tabindex="-1">Register</a>
        </div>
    </div>
</div>
</div>
