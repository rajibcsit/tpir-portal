<!DOCTYPE html><html lang="bn"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><script src="https://cdn.tailwindcss.com"></script><link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&display=swap" rel="stylesheet"><style>body{font-family:'Hind Siliguri',sans-serif}</style><title>Login | Polytechnic Portal</title></head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4"><div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-8">
<div class="text-center text-4xl mb-3">🎓</div><h1 class="text-2xl font-bold text-center">Polytechnic Portal</h1><p class="text-center text-slate-500 mb-7">Admin · Teacher · Accountant · Student Login</p>
<?php if($errors->any()): ?><div class="bg-rose-50 text-rose-700 p-3 rounded-lg mb-4"><?php echo e($errors->first()); ?></div><?php endif; ?>
<form method="POST" action="<?php echo e(route('login.post')); ?>" class="space-y-4"><?php echo csrf_field(); ?>
<div><label class="block mb-1 font-medium">ইমেইল</label><input name="email" type="email" value="<?php echo e(old('email')); ?>" required class="w-full border rounded-lg px-3 py-3 focus:ring-2 focus:ring-blue-500"></div>
<div><label class="block mb-1 font-medium">পাসওয়ার্ড</label><input name="password" type="password" required class="w-full border rounded-lg px-3 py-3"></div>
<button class="w-full bg-blue-700 hover:bg-blue-800 text-white rounded-lg py-3 font-semibold">লগইন করুন</button></form>
<p class="text-xs text-slate-400 mt-6 text-center">Login access is assigned by the institute administrator.</p></div></body></html>
<?php /**PATH E:\TPIR\tpir-portal\resources\views\auth\login.blade.php ENDPATH**/ ?>