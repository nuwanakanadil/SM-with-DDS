<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import TextInput from '@/components/TextInput.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';
import profile from '@/routes/profile';
import verification from '@/routes/verification';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps<{
    mustVerifyEmail?: Boolean;
    status?: String;
}>();

const page = usePage();
const fallbackUser = {
    name: '',
    email: '',
    email_verified_at: null,
    avatar: null,
};

const user = computed(() => page.props.auth.user ?? fallbackUser);

const initials = computed(() => getInitials(user.value.name));

const form = useForm({
    name: user.value.name,
    email: user.value.email,
});
</script>

<template>
    <section class="space-y-7">
        <div class="flex items-center gap-5">
            <div class="space-y-3">
                <Avatar class="size-28 border border-border bg-secondary">
                    <AvatarImage v-if="user.avatar" :src="user.avatar" :alt="user.name" />
                    <AvatarFallback class="text-2xl font-semibold text-foreground">
                        {{ initials || 'U' }}
                    </AvatarFallback>
                </Avatar>

                <p class="text-xs text-muted-foreground">
                    Profile photo upload is temporarily disabled.
                </p>
            </div>

            <div>
                <p class="text-lg font-semibold text-foreground">
                    {{ user.name || 'User Profile' }}
                </p>
                <p class="mt-1 text-sm text-muted-foreground">
                    Manage your basic account details.
                </p>
            </div>
        </div>

        <form
            @submit.prevent="form.submit(profile.update())"
            class="space-y-6"
        >
            <div class="space-y-2">
                <InputLabel for="name" value="Full Name" />

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
                <InputLabel for="email" value="Email Address" />

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
                    class="mt-3 rounded-2xl border border-emerald-500/25 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-600 dark:text-emerald-300"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

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
