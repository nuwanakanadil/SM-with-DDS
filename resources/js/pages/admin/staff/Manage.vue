<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import PageHero from '@/components/PageHero.vue';
import { Badge } from '@/components/ui/badge';
import { Button, IconButton } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import AdminLayout from '@/layouts/admin/Layout.vue';
import type { StaffAccount } from '@/types/staff';
import { generatePassword } from '@/utils/password';
import { Head, useForm } from '@inertiajs/vue3';
import { BriefcaseBusiness, KeyRound, RefreshCw, Save, UserRound, X } from 'lucide-vue-next';

const props = defineProps<{
    staff?: StaffAccount;
}>();

const isEdit = Boolean(props.staff);

const form = useForm({
    name: props.staff?.name ?? '',
    email: props.staff?.email ?? '',
    password: '',
});

const submit = () => {
    if (props.staff) {
        form.put(`/admin/staff/${props.staff.id}`);
        return;
    }

    form.post('/admin/staff');
};

const fillGeneratedPassword = () => {
    form.password = generatePassword();
};
</script>

<template>
    <Head :title="isEdit ? 'Edit Staff Account' : 'Add Staff Account'" />
    <AppLayout
        :breadcrumbs="[
            { title: 'Admin' },
            { title: 'Staff Accounts', href: '/admin/staff' },
            { title: isEdit ? 'Edit' : 'Create' },
        ]"
    >
        <AdminLayout>
            <div class="space-y-6">
                <PageHero
                    eyebrow="Staff Access"
                    :title="isEdit ? 'Edit Staff Account' : 'Create Staff Account'"
                    description="Set up staff login credentials and email the account details automatically when a new team member is added."
                >
                    <template #meta>
                        <Badge variant="outline" class="rounded-full px-4 py-1.5">
                            <BriefcaseBusiness class="size-3.5" />
                            {{ isEdit ? 'Update staff account' : 'New staff account' }}
                        </Badge>
                    </template>
                </PageHero>

                <Card class="section-card max-w-4xl">
                    <CardHeader>
                        <CardTitle>Staff Details</CardTitle>
                        <CardDescription>
                            Staff members receive their login details by email as soon as the account is created.
                        </CardDescription>
                    </CardHeader>

                    <CardContent>
                        <form class="space-y-8" @submit.prevent="submit">
                            <section class="space-y-4">
                                <div class="flex items-center gap-2">
                                    <UserRound class="size-4 text-primary" />
                                    <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-muted-foreground">
                                        Profile
                                    </h3>
                                </div>
                                <div class="grid gap-5 md:grid-cols-2">
                                    <div class="space-y-2 md:col-span-2">
                                        <Label for="name">Full Name</Label>
                                        <Input id="name" v-model="form.name" placeholder="Enter staff member name" />
                                        <InputError :message="form.errors.name" />
                                    </div>
                                    <div class="space-y-2 md:col-span-2">
                                        <Label for="email">Email Address</Label>
                                        <Input id="email" v-model="form.email" type="email" placeholder="Enter email address" />
                                        <InputError :message="form.errors.email" />
                                    </div>
                                </div>
                            </section>

                            <section class="space-y-4">
                                <div class="flex items-center gap-2">
                                    <KeyRound class="size-4 text-primary" />
                                    <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-muted-foreground">
                                        Access Setup
                                    </h3>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between gap-3">
                                        <Label for="password">Password</Label>
                                        <Button type="button" variant="outline" size="sm" @click="fillGeneratedPassword">
                                            <RefreshCw class="size-4" />
                                            Generate Random
                                        </Button>
                                    </div>
                                    <Input id="password" v-model="form.password" type="password" placeholder="Leave blank to auto-generate a password" />
                                    <p class="text-sm text-muted-foreground">
                                        New accounts are emailed their login details automatically. Updating this later changes the saved password only.
                                    </p>
                                    <InputError :message="form.errors.password" />
                                </div>
                            </section>

                            <div class="flex flex-wrap items-center justify-end gap-3 border-t border-border/80 pt-6">
                                <Button :disabled="form.processing" size="lg">
                                    <Save class="size-4" />
                                    {{ form.processing ? 'Saving...' : 'Save Staff Account' }}
                                </Button>
                                <IconButton :icon="X" link="/admin/staff" variant="outline" size="lg">
                                    Cancel
                                </IconButton>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    </AppLayout>
</template>
