<x-layouts.app>
    <x-slot name="title">{{ config('app.name') }} – {{ __('Privacy Policy') }}</x-slot>

    <section class="py-16 lg:py-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="mb-12 text-center">
                <h1 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4">
                    {{ __('Privacy Policy') }}
                </h1>
                <p class="text-lg text-gray-600">
                    {{ __('Information about how personal data is processed in this CRM system') }}
                </p>
                <p class="text-sm text-gray-400 mt-2">
                    {{ __('Last updated') }}: {{ now()->format('Y.m.d.') }}
                </p>
            </div>

            <div class="prose prose-indigo max-w-none">
                {{-- 1. Introduction --}}
                <h2>{{ __('1. Data Processing Notice') }}</h2>
                <p>{{ __('This CRM system processes personal data in accordance with the General Data Protection Regulation (GDPR) - Regulation (EU) 2016/679. Below you will find information about the types of data processed, the purposes of processing, and the rights of data subjects.') }}</p>

                {{-- 2. Data Controller --}}
                <h2>{{ __('2. Data Controller') }}</h2>
                <ul>
                    <li><strong>{{ __('Company name') }}:</strong> Cégem360 Kft.</li>
                    <li><strong>{{ __('Registered address') }}:</strong> —</li>
                    <li><strong>{{ __('Email') }}:</strong> info@cegem360.eu</li>
                    <li><strong>{{ __('Website') }}:</strong> https://cegem360.eu</li>
                </ul>

                {{-- 3. Types of Data --}}
                <h2>{{ __('3. Types of Personal Data Processed') }}</h2>
                <ul>
                    <li><strong>{{ __('Customer Identification Data') }}</strong>: {{ __('Name, unique identifier, customer type (individual/company)') }}</li>
                    <li><strong>{{ __('Contact Information') }}</strong>: {{ __('Email address, phone number, postal address') }}</li>
                    <li><strong>{{ __('Company Data') }}</strong>: {{ __('Tax number, registration number (for company customers)') }}</li>
                    <li><strong>{{ __('Transaction Data') }}</strong>: {{ __('Quotes, orders, invoices, and related financial data') }}</li>
                    <li><strong>{{ __('Communication Records') }}</strong>: {{ __('Interaction history, notes, complaints') }}</li>
                    <li><strong>{{ __('Consent Records') }}</strong>: {{ __('Records of data processing consents and their status') }}</li>
                </ul>

                {{-- 4. Legal Basis --}}
                <h2>{{ __('4. Legal Basis for Processing') }}</h2>
                <ul>
                    <li><strong>{{ __('Contract Performance') }}</strong> ({{ __('Art. 6(1)(b) GDPR') }}): {{ __('Processing necessary for quotes, orders, and invoices') }}</li>
                    <li><strong>{{ __('Legal Obligation') }}</strong> ({{ __('Art. 6(1)(c) GDPR') }}): {{ __('Tax and accounting record retention requirements') }}</li>
                    <li><strong>{{ __('Legitimate Interest') }}</strong> ({{ __('Art. 6(1)(f) GDPR') }}): {{ __('Customer relationship management, service improvement') }}</li>
                    <li><strong>{{ __('Consent') }}</strong> ({{ __('Art. 6(1)(a) GDPR') }}): {{ __('Marketing communications, newsletter, profiling') }}</li>
                </ul>

                {{-- 5. Data Retention --}}
                <h2>{{ __('5. Data Retention Periods') }}</h2>
                <ul>
                    <li><strong>{{ __('Customer Data') }}</strong>: {{ __('Retained for the duration of the business relationship, plus any legally required retention period') }}</li>
                    <li><strong>{{ __('Financial Records') }}</strong>: {{ __('8 years from the date of the transaction (Hungarian accounting regulations)') }}</li>
                    <li><strong>{{ __('Marketing Consent') }}</strong>: {{ __('Until consent is withdrawn') }}</li>
                    <li><strong>{{ __('Communication Records') }}</strong>: {{ __('5 years from the date of the interaction') }}</li>
                </ul>

                {{-- 6. Data Subject Rights --}}
                <h2>{{ __('6. Data Subject Rights') }}</h2>
                <p>{{ __('Under GDPR, data subjects have the following rights:') }}</p>
                <ul>
                    <li><strong>{{ __('Right of Access') }}</strong> ({{ __('Art. 15') }}): {{ __('You may request a copy of your personal data held by us.') }}</li>
                    <li><strong>{{ __('Right to Rectification') }}</strong> ({{ __('Art. 16') }}): {{ __('You may request correction of inaccurate personal data.') }}</li>
                    <li><strong>{{ __('Right to Erasure') }}</strong> ({{ __('Art. 17') }}): {{ __('You may request deletion of your personal data, subject to legal retention obligations.') }}</li>
                    <li><strong>{{ __('Right to Data Portability') }}</strong> ({{ __('Art. 20') }}): {{ __('You may request your data in a structured, machine-readable format.') }}</li>
                    <li><strong>{{ __('Right to Withdraw Consent') }}</strong> ({{ __('Art. 7(3)') }}): {{ __('You may withdraw your consent at any time without affecting the lawfulness of prior processing.') }}</li>
                </ul>

                {{-- 7. Exercising Rights --}}
                <h2>{{ __('7. How to Exercise Your Rights') }}</h2>
                <p>{{ __('To exercise any of the above rights, please contact us at:') }}</p>
                <ul>
                    <li><strong>{{ __('Email') }}:</strong> info@cegem360.eu</li>
                </ul>
                <p>{{ __('We will respond to your request within 30 days of receipt.') }}</p>

                {{-- 8. Cookies --}}
                <h2>{{ __('8. Cookies') }}</h2>
                <p>{{ __('This website uses essential cookies for authentication and language preferences. These cookies are necessary for the proper functioning of the website and cannot be disabled.') }}</p>
                <ul>
                    <li><strong>locale</strong>: {{ __('Stores your language preference (1 year)') }}</li>
                    <li><strong>laravel_session</strong>: {{ __('Session identifier (2 hours)') }}</li>
                    <li><strong>XSRF-TOKEN</strong>: {{ __('Security token to prevent cross-site request forgery (2 hours)') }}</li>
                </ul>

                {{-- 9. Supervisory Authority --}}
                <h2>{{ __('9. Supervisory Authority') }}</h2>
                <p>{{ __('If you believe that your data protection rights have been violated, you may lodge a complaint with the National Authority for Data Protection and Freedom of Information (NAIH):') }}</p>
                <ul>
                    <li><strong>{{ __('Name') }}:</strong> Nemzeti Adatvédelmi és Információszabadság Hatóság</li>
                    <li><strong>{{ __('Address') }}:</strong> 1055 Budapest, Falk Miksa utca 9-11.</li>
                    <li><strong>{{ __('Email') }}:</strong> ugyfelszolgalat@naih.hu</li>
                    <li><strong>{{ __('Website') }}:</strong> https://naih.hu</li>
                </ul>
            </div>
        </div>
    </section>

    <x-layouts.footer />
</x-layouts.app>
