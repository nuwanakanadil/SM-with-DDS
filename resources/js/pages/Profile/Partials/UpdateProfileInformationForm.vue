<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import TextInput from '@/components/TextInput.vue';
import profile from '@/routes/profile';
import verification from '@/routes/verification';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps<{
    mustVerifyEmail?: Boolean;
    status?: String;
}>();

const user = usePage().props.auth.user ?? {
    name: '',
    email: '',
    email_verified_at: null,
};

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section class="space-y-7">
        <header>
            <p class="eyebrow-label">Profile information</p>
            <h2 class="mt-2 text-2xl font-bold tracking-[-0.03em] text-foreground">
                Profile Information
            </h2>

            <p class="mt-2 text-[15px] leading-7 text-muted-foreground">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            @submit.prevent="form.submit(profile.update())"
            class="space-y-6"
        >
            <div class="space-y-2">
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError :message="form.errors.name" />
            </div>

            <div class="space-y-2">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="rounded-[1.35rem] border border-border bg-secondary/55 px-4 py-4 text-sm leading-7 text-foreground">
                    Your email address is unverified.
                    <Link
                        :href="verification.send()"
                        as="button"
                        class="ml-1 rounded-md text-sm font-semibold text-primary underline-offset-4 hover:underline focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-3 rounded-2xl border border-[#bbf7d0] bg-[#f0fdf4] px-4 py-3 text-sm font-medium text-[#16A34A]"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save changes</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm font-medium text-muted-foreground"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
