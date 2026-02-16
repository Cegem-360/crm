<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Introduction --}}
        <x-filament::section>
            <x-slot name="heading">{{ __('Data Processing Notice') }}</x-slot>
            <x-slot name="description">{{ __('Information about how personal data is processed in this CRM system') }}</x-slot>

            <div class="prose dark:prose-invert max-w-none">
                <p>{{ __('This CRM system processes personal data in accordance with the General Data Protection Regulation (GDPR) - Regulation (EU) 2016/679. Below you will find information about the types of data processed, the purposes of processing, and the rights of data subjects.') }}</p>
            </div>
        </x-filament::section>

        {{-- Types of Data Processed --}}
        <x-filament::section>
            <x-slot name="heading">{{ __('Types of Personal Data Processed') }}</x-slot>

            <div class="prose dark:prose-invert max-w-none">
                <ul>
                    <li><strong>{{ __('Customer Identification Data') }}</strong>: {{ __('Name, unique identifier, customer type (individual/company)') }}</li>
                    <li><strong>{{ __('Contact Information') }}</strong>: {{ __('Email address, phone number, postal address') }}</li>
                    <li><strong>{{ __('Company Data') }}</strong>: {{ __('Tax number, registration number (for company customers)') }}</li>
                    <li><strong>{{ __('Transaction Data') }}</strong>: {{ __('Quotes, orders, invoices, and related financial data') }}</li>
                    <li><strong>{{ __('Communication Records') }}</strong>: {{ __('Interaction history, notes, complaints') }}</li>
                    <li><strong>{{ __('Consent Records') }}</strong>: {{ __('Records of data processing consents and their status') }}</li>
                </ul>
            </div>
        </x-filament::section>

        {{-- Legal Basis --}}
        <x-filament::section>
            <x-slot name="heading">{{ __('Legal Basis for Processing') }}</x-slot>

            <div class="prose dark:prose-invert max-w-none">
                <ul>
                    <li><strong>{{ __('Contract Performance') }}</strong> ({{ __('Art. 6(1)(b) GDPR') }}): {{ __('Processing necessary for quotes, orders, and invoices') }}</li>
                    <li><strong>{{ __('Legal Obligation') }}</strong> ({{ __('Art. 6(1)(c) GDPR') }}): {{ __('Tax and accounting record retention requirements') }}</li>
                    <li><strong>{{ __('Legitimate Interest') }}</strong> ({{ __('Art. 6(1)(f) GDPR') }}): {{ __('Customer relationship management, service improvement') }}</li>
                    <li><strong>{{ __('Consent') }}</strong> ({{ __('Art. 6(1)(a) GDPR') }}): {{ __('Marketing communications, newsletter, profiling') }}</li>
                </ul>
            </div>
        </x-filament::section>

        {{-- Data Retention --}}
        <x-filament::section>
            <x-slot name="heading">{{ __('Data Retention Periods') }}</x-slot>

            <div class="prose dark:prose-invert max-w-none">
                <ul>
                    <li><strong>{{ __('Customer Data') }}</strong>: {{ __('Retained for the duration of the business relationship, plus any legally required retention period') }}</li>
                    <li><strong>{{ __('Financial Records') }}</strong>: {{ __('8 years from the date of the transaction (Hungarian accounting regulations)') }}</li>
                    <li><strong>{{ __('Marketing Consent') }}</strong>: {{ __('Until consent is withdrawn') }}</li>
                    <li><strong>{{ __('Communication Records') }}</strong>: {{ __('5 years from the date of the interaction') }}</li>
                </ul>
            </div>
        </x-filament::section>

        {{-- Data Subject Rights --}}
        <x-filament::section>
            <x-slot name="heading">{{ __('Data Subject Rights') }}</x-slot>

            <div class="prose dark:prose-invert max-w-none">
                <p>{{ __('Under GDPR, data subjects have the following rights:') }}</p>
                <ul>
                    <li><strong>{{ __('Right of Access') }}</strong> ({{ __('Art. 15') }}): {{ __('Data subjects can request a copy of their personal data. Use the "Export Data (GDPR)" button on the customer profile.') }}</li>
                    <li><strong>{{ __('Right to Rectification') }}</strong> ({{ __('Art. 16') }}): {{ __('Data subjects can request correction of inaccurate data. Edit the customer record directly.') }}</li>
                    <li><strong>{{ __('Right to Erasure') }}</strong> ({{ __('Art. 17') }}): {{ __('Data subjects can request deletion of their data. Use the "Forget Me (GDPR)" button on the customer profile. Note: financial records may be retained per legal obligations.') }}</li>
                    <li><strong>{{ __('Right to Data Portability') }}</strong> ({{ __('Art. 20') }}): {{ __('Data subjects can request their data in a structured, machine-readable format. The export function provides JSON output.') }}</li>
                    <li><strong>{{ __('Right to Withdraw Consent') }}</strong> ({{ __('Art. 7(3)') }}): {{ __('Consents can be revoked at any time via the Consents tab on the customer profile.') }}</li>
                </ul>
            </div>
        </x-filament::section>

        {{-- How to Process Requests --}}
        <x-filament::section>
            <x-slot name="heading">{{ __('Processing GDPR Requests') }}</x-slot>

            <div class="prose dark:prose-invert max-w-none">
                <ol>
                    <li>{{ __('Navigate to the customer profile in the Customers section') }}</li>
                    <li>{{ __('For data export: Click "Export Data (GDPR)" in the header actions') }}</li>
                    <li>{{ __('For data deletion: Click "Forget Me (GDPR)" in the header actions') }}</li>
                    <li>{{ __('For consent management: Open the "Consents" tab on the customer profile') }}</li>
                    <li>{{ __('All actions are logged in the activity log for accountability') }}</li>
                </ol>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
