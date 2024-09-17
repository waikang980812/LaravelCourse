<template>
    <div>You need be verified first to see this page!</div>
    <div>
        <Link :href="route('verification.send')" method="post" as="button" class="text-indigo-600">Resend Verification
        email</Link>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

</script>