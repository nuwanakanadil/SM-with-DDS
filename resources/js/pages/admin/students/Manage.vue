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
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import AdminLayout from '@/layouts/admin/Layout.vue';
import type { Student } from '@/types/student';
import { Head, useForm } from '@inertiajs/vue3';
import {
    BadgeCheck,
    BookUser,
    IdCard,
    Mail,
    Phone,
    Save,
    UserRound,
    X,
} from 'lucide-vue-next';
import students from '@/routes/admin/students';

const props = defineProps<{
    student?: Student;
    gradeOptions: string[];
}>();

const isEdit = Boolean(props.student);

const form = useForm({
    admission_no: props.student?.admission_no ?? '',
    first_name: props.student?.first_name ?? '',
    last_name: props.student?.last_name ?? '',
    email: props.student?.email ?? '',
    phone: props.student?.phone ?? '',
    class_name: props.student?.class_name ?? '',
    password: '',
    is_active: props.student?.is_active ?? true,
});

const submit = () =>
    props.student
        ? form.submit(students.update(props.student))
        : form.submit(students.store());

const updateClassName = (value: unknown) => {
    const normalizedValue = value == null ? '' : String(value);
    form.class_name = !normalizedValue || normalizedValue === '__none__' ? '' : normalizedValue;
};
</script>

<template>
    <Head :title="isEdit ? 'Edit Student' : 'Add Student'" />
    <AppLayout
        :breadcrumbs="[
            { title: 'Admin' },
            { title: 'Students', href: students.index.url() },
            { title: isEdit ? 'Edit' : 'Create' },
        ]"
    >
        <AdminLayout>
            <div class="space-y-6">
                <PageHero
                    eyebrow="Student Profile"
                    :title="isEdit ? 'Edit Student Record' : 'Create Student Record'"
                    description="Capture the identity, contact, class, and access details required for a professional academic workflow."
                >
                    <template #meta>
                        <Badge variant="outline" class="rounded-full px-4 py-1.5">
                            <BookUser class="size-3.5" />
                            {{ isEdit ? 'Update existing record' : 'New record' }}
                        </Badge>
                    </template>
                    <template #actions>
                        <IconButton :icon="BookUser" :link="students.index()" variant="outline">
                            Back to list
                        </IconButton>
                    </template>
                </PageHero>

                <Card class="section-card">
                    <CardHeader>
                        <CardTitle>Student Details</CardTitle>
                        <CardDescription>
                            Organised sections help keep records accurate for authentication, marks entry, and ranking.
                        </CardDescription>
                    </CardHeader>

                    <CardContent>
                        <form class="space-y-8" @submit.prevent="submit">
                            <section class="space-y-4">
                                <div class="flex items-center gap-2">
                                    <IdCard class="size-4 text-primary" />
                                    <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-muted-foreground">
                                        Academic Identity
                                    </h3>
                                </div>
                                <div class="grid gap-5 md:grid-cols-2">
                                    <div class="space-y-2">
                                        <Label for="admission_no">Admission Number</Label>
                                        <Input id="admission_no" v-model="form.admission_no" placeholder="Enter admission number" />
                                        <InputError :message="form.errors.admission_no" />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="class_name">Class</Label>
                                        <Select
                                            :model-value="form.class_name || '__none__'"
                                            @update:model-value="updateClassName"
                                        >
                                            <SelectTrigger id="class_name" class="w-full">
                                                <SelectValue placeholder="Select grade" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="__none__">Not assigned</SelectItem>
                                                <SelectItem v-for="grade in props.gradeOptions" :key="grade" :value="grade">
                                                    {{ grade }}
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                        <InputError :message="form.errors.class_name" />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="first_name">First Name</Label>
                                        <Input id="first_name" v-model="form.first_name" placeholder="Enter first name" />
                                        <InputError :message="form.errors.first_name" />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="last_name">Last Name</Label>
                                        <Input id="last_name" v-model="form.last_name" placeholder="Enter last name" />
                                        <InputError :message="form.errors.last_name" />
                                    </div>
                                </div>
                            </section>

                            <section class="space-y-4">
                                <div class="flex items-center gap-2">
                                    <Mail class="size-4 text-primary" />
                                    <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-muted-foreground">
                                        Contact Information
                                    </h3>
                                </div>
                                <div class="grid gap-5 md:grid-cols-2">
                                    <div class="space-y-2">
                                        <Label for="email">Email</Label>
                                        <Input id="email" v-model="form.email" type="email" placeholder="Enter email address" />
                                        <InputError :message="form.errors.email" />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="phone">Phone</Label>
                                        <Input id="phone" v-model="form.phone" placeholder="Enter phone number" />
                                        <InputError :message="form.errors.phone" />
                                    </div>
                                </div>
                            </section>

                            <section v-if="!isEdit" class="space-y-4">
                                <div class="flex items-center gap-2">
                                    <UserRound class="size-4 text-primary" />
                                    <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-muted-foreground">
                                        Access Setup
                                    </h3>
                                </div>
                                <div class="space-y-2">
                                    <Label for="password">Password</Label>
                                    <Input id="password" v-model="form.password" type="password" placeholder="Set an optional password" />
                                    <p class="text-sm text-muted-foreground">
                                        Leave blank if the password will be assigned later.
                                    </p>
                                    <InputError :message="form.errors.password" />
                                </div>
                            </section>

                            <section class="space-y-4">
                                <div class="flex items-center gap-2">
                                    <BadgeCheck class="size-4 text-primary" />
                                    <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-muted-foreground">
                                        Status
                                    </h3>
                                </div>
                                <div class="rounded-[1.5rem] border border-dashed border-border bg-secondary/50 p-5">
                                    <label class="flex items-start gap-3">
                                        <input
                                            v-model="form.is_active"
                                            type="checkbox"
                                            class="mt-1 h-4 w-4 rounded border-input text-primary focus:ring-ring"
                                        >
                                        <span>
                                            <span class="block font-semibold text-foreground">Active student</span>
                                            <span class="mt-1 block text-sm text-muted-foreground">
                                                Active students can be included in future result entry and ranking flows.
                                            </span>
                                        </span>
                                    </label>
                                    <InputError class="mt-3" :message="form.errors.is_active" />
                                </div>
                            </section>

                            <div class="flex flex-wrap items-center justify-end gap-3 border-t border-border/80 pt-6">
                                <Button :disabled="form.processing" size="lg">
                                    <Save class="size-4" />
                                    {{ form.processing ? 'Saving...' : 'Save Student' }}
                                </Button>
                                <IconButton :icon="X" :link="students.index()" variant="outline" size="lg">
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
