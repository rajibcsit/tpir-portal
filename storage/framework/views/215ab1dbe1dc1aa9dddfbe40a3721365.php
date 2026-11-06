<?php $__env->startSection('title','ফি ও পেমেন্ট'); ?>
<?php $__env->startSection('page-heading','ফি ও পেমেন্ট ব্যবস্থাপনা'); ?>
<?php $__env->startSection('content'); ?>
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div><div class="text-sm font-semibold text-blue-700">ACCOUNTS / COLLECTION</div><h1 class="mt-1 text-2xl font-bold text-slate-900">ফি ও পেমেন্ট ব্যবস্থাপনা</h1><p class="mt-1 text-sm text-slate-500">হাজারো শিক্ষার্থীর মধ্যেও Roll, Technology, Semester ও Session দিয়ে দ্রুত পেমেন্ট খুঁজুন।</p></div>
    <div class="flex gap-2 no-print"><a href="<?php echo e(route('payments.print', request()->query())); ?>" target="_blank" class="btn border bg-white">🖨 Print Report</a><a href="<?php echo e(route('payments.create')); ?>" class="btn btn-primary">＋ নতুন পেমেন্ট এন্ট্রি</a></div>
</div>
<div class="mb-5 grid gap-3 sm:grid-cols-3">
    <div class="panel p-4"><div class="text-sm text-slate-500">মোট পেমেন্ট রেকর্ড</div><div class="mt-2 text-2xl font-bold text-slate-900"><?php echo e(number_format($stats['total'])); ?></div></div>
    <div class="panel p-4"><div class="text-sm text-slate-500">মোট আদায়</div><div class="mt-2 text-2xl font-bold text-emerald-700">৳<?php echo e(number_format($stats['collected'], 2)); ?></div></div>
    <div class="panel p-4"><div class="text-sm text-slate-500">মোট বকেয়া</div><div class="mt-2 text-2xl font-bold text-rose-700">৳<?php echo e(number_format($stats['due'], 2)); ?></div></div>
</div>
<section class="panel mb-5 overflow-hidden">
    <div class="border-b border-slate-100 bg-slate-50/80 px-5 py-4"><div class="font-bold">শিক্ষার্থী ও পেমেন্ট সার্চ</div><p class="mt-1 text-xs text-slate-500">একাধিক ফিল্টার একসঙ্গে ব্যবহার করতে পারবেন।</p></div>
    <form method="GET" class="grid gap-3 p-5 sm:grid-cols-2 xl:grid-cols-4">
        <div class="sm:col-span-2"><label class="mb-1 block text-xs font-semibold text-slate-600">Roll / শিক্ষার্থীর নাম / মোবাইল</label><input name="search" value="<?php echo e(request('search')); ?>" class="field" placeholder="Roll বা নাম লিখুন"></div>
        
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Technology</label><select name="technology" class="field"><option value="">সব Technology</option><?php $__currentLoopData = $technologies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($v); ?>" <?php if(request('technology')===$v): echo 'selected'; endif; ?>><?php echo e($v); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Semester</label><select name="semester" class="field"><option value="">সব Semester</option><?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($v); ?>" <?php if(request('semester')===$v): echo 'selected'; endif; ?>><?php echo e($v); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Session</label><select name="session" class="field"><option value="">সব Session</option><?php $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($v); ?>" <?php if(request('session')===$v): echo 'selected'; endif; ?>><?php echo e($v); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">পেমেন্ট অবস্থা</label><select name="filter" class="field"><option value="">সব পেমেন্ট</option><option value="due" <?php if(request('filter')==='due'): echo 'selected'; endif; ?>>শুধু বকেয়া</option></select></div>
        <div class="flex items-end gap-2 xl:col-span-4"><button class="btn btn-primary">⌕ সার্চ করুন</button><a href="<?php echo e(route('payments.index')); ?>" class="btn border border-slate-200 bg-white">সব রিসেট</a></div>
    </form>
</section>
<div class="panel overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-5 py-4"><div class="font-bold">পেমেন্ট রেকর্ড</div><div class="text-xs text-slate-500"><?php echo e($payments->total()); ?>টি রেকর্ড পাওয়া গেছে</div></div>
    <div class="overflow-x-auto"><table class="w-full min-w-[850px] text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-3 text-left">শিক্ষার্থী</th><th class="px-4 py-3 text-left">Technology / Semester</th><th class="px-4 py-3 text-left">ফি বিবরণ</th><th class="px-4 py-3 text-right">নির্ধারিত</th><th class="px-4 py-3 text-right">পরিশোধ</th><th class="px-4 py-3 text-right">বকেয়া</th><th class="px-5 py-3 text-center">অ্যাকশন</th></tr></thead>
        <tbody class="divide-y divide-slate-100">
        <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="hover:bg-slate-50/70"><td class="px-5 py-4"><div class="font-bold text-slate-900"><?php echo e($p->student?->name ?? 'Deleted student'); ?></div><div class="mt-1 text-xs text-slate-500">Roll: <?php echo e($p->student?->student_id ?? '—'); ?> · <?php echo e($p->student?->phone ?? 'No phone'); ?></div></td><td class="px-4 py-4"><div class="font-medium"><?php echo e($p->student?->technology ?? '—'); ?></div><div class="mt-1 text-xs text-slate-500"><?php echo e($p->student?->semester ?? '—'); ?> Semester · <?php echo e($p->student?->session ?? '—'); ?></div></td><td class="px-4 py-4"><div class="font-medium"><?php echo e($p->title); ?></div><div class="mt-1 text-xs text-slate-500"><?php echo e($p->paid_date?->format('d M Y') ?? 'তারিখ নেই'); ?> · <?php echo e($p->method ?? '—'); ?></div></td><td class="px-4 py-4 text-right font-medium">৳<?php echo e(number_format($p->amount_due,2)); ?></td><td class="px-4 py-4 text-right font-semibold text-emerald-700">৳<?php echo e(number_format($p->amount_paid,2)); ?></td><td class="px-4 py-4 text-right font-semibold <?php echo e($p->due > 0 ? 'text-rose-700' : 'text-emerald-700'); ?>">৳<?php echo e(number_format($p->due,2)); ?></td><td class="px-5 py-4"><div class="flex justify-center gap-3"><a class="font-semibold text-blue-700 hover:underline" href="<?php echo e(route('payments.receipt',$p)); ?>" target="_blank">Receipt</a><a class="font-semibold text-blue-700 hover:underline" href="<?php echo e(route('payments.edit',$p)); ?>">এডিট</a><form method="POST" action="<?php echo e(route('payments.destroy',$p)); ?>" onsubmit="return confirm('এই পেমেন্ট রেকর্ড মুছে ফেলবেন?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="font-semibold text-rose-600 hover:underline">ডিলিট</button></form></div></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="7" class="px-5 py-14 text-center"><div class="text-3xl">⌕</div><div class="mt-2 font-semibold text-slate-700">কোনো পেমেন্ট রেকর্ড পাওয়া যায়নি</div><div class="mt-1 text-sm text-slate-500">সার্চ বা ফিল্টার পরিবর্তন করে আবার চেষ্টা করুন।</div></td></tr>
        <?php endif; ?>
        </tbody>
    </table></div>
    <div class="border-t border-slate-100 px-5 py-4"><?php echo e($payments->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\TPIR\tpir-portal\resources\views\payments\index.blade.php ENDPATH**/ ?>