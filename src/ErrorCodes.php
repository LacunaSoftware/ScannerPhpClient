<?php


namespace Lacuna\Scanner;


class ErrorCodes
{
    const UNKNOWN = 'Unknown';
    const BAD_CERTIFICATE = 'BadCertificate';
    const SUBSCRIPTION_NOT_FOUND = 'SubscriptionNotFound';
    const SUBSCRIPTION_REQUIRED = 'SubscriptionRequired';
    const APPLICATION_NOT_FOUND = 'ApplicationNotFound';
    const BAD_SUBSCRIPTION = 'BadSubscription';
    const NORMAL_SUBSCRIPTION_REQUIRED = 'NormalSubscriptionRequired';
    const NOT_REGISTERED = 'NotRegistered';
    const AGENT_NOT_FOUND = 'AgentNotFound';
    const AGENT_IS_NOT_USER = 'AgentIsNotUser';
    const INVITE_NOT_FOUND = 'InviteNotFound';
    const APPLICATION_KEY_NOT_FOUND = 'ApplicationKeyNotFound';
    const APPLICATION_DELETED = 'ApplicationDeleted';
    const APPLICATION_NAME_EXISTS = 'ApplicationNameExists';
    const USER_NOT_FOUND = 'UserNotFound';
    const INVALID_CLAIM_CODE = 'InvalidClaimCode';
    const SEARCH_NOT_SUPPORTED = 'SearchNotSupported';
    const EMAIL_DISABLED = 'EmailDisabled';
    const HTTPS_REQUIRED = 'HttpsRequired';
    const ROLE_MISSING = 'RoleMissing';
    const UPLOAD_NOT_FOUND = 'UploadNotFound';
    const PUBLIC_KEY_AUTHENTICATION_EXPIRED = 'PublicKeyAuthenticationExpired';
    const NONCE_REUSED = 'NonceReused';
    const INVALID_NONCE_SIGNATURE = 'InvalidNonceSignature';
    const PUBLIC_KEY_AUTHENTICATION_SESSION_MISSING = 'PublicKeyAuthenticationSessionMissing';
    const INVALID_PUBLIC_KEY_AUTHENTICATION_SESSION = 'InvalidPublicKeyAuthenticationSession';
    const CHILD_NAMESPACE_NOT_FOUND = 'ChildNamespaceNotFound';
    const OIDC_DISABLED = 'OidcDisabled';
    const ROOT_PASSWORD_HASH_NOT_FOUND = 'RootPasswordHashNotFound';
    const GRANT_ID_ERROR = 'GrantIdError';
    const INVALID_SCAN_SESSION_TICKET = 'InvalidScanSessionTicket';
    const SCAN_SESSION_NOT_FOUND = 'ScanSessionNotFound';
    const DOCUMENT_NOT_FOUND = 'DocumentNotFound';
    const SCAN_SESSION_STATE_MISSING = 'ScanSessionStateMissing';
    const SCAN_SESSION_STATE_EXPIRED = 'ScanSessionStateExpired';
    const SCAN_SESSION_COMPLETED = 'ScanSessionCompleted';
    const SCAN_SESSION_EXPIRED = 'ScanSessionExpired';
    const INVALID_SCAN_SESSION_STATE = 'InvalidScanSessionState';
    const INVALID_ISO_8601_DATE = 'InvalidIso8601Date';
    const SCANNED_DOCUMENT_NOT_UPLOADED = 'ScannedDocumentNotUploaded';
    const METADATA_NOT_SUBMITTED = 'MetadataNotSubmitted';
    const DOCUMENT_SIGNATURE_NOT_STARTED = 'DocumentSignatureNotStarted';
    const DOCUMENT_SIGNATURE_NOT_SUBMITTED = 'DocumentSignatureNotSubmitted';
    const KEYWORDS_REQUIRED = 'KeywordsRequired';
    const KEYWORD_TOO_lONG = 'KeywordTooLong';
    const INVALID_METADATA_PRESETS = 'InvalidMetadataPresets';
    const INVALID_CAPTCHA = 'InvalidCaptcha';
}