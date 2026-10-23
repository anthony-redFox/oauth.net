<?php
$page_title = "Workload Authorization Grant - OAuth 2.0";
$page_section = "";
$page_secondary = "";
$page_meta_description = "Workload Authorization Grant (WAG) - Platform-signed JWT authorization grants for agents and workloads";
require('../../includes/_header.php');
?>
<div class="container">
  <div>

    <h2>Workload Authorization Grant</h2>

    <p><a href="https://datatracker.ietf.org/doc/draft-carleton-workload-authz-grant/" class="rfc">datatracker.ietf.org/doc/draft-carleton-workload-authz-grant</a></p>

    <p>The Workload Authorization Grant (WAG) is a profile of the <a href="https://datatracker.ietf.org/doc/html/rfc7523">JWT Authorization Grant</a> that lets a workload hosted on a platform, such as an AI agent, get access tokens from a third party's authorization server. The platform signs a short-lived JWT naming one agent, and the agent presents it at the token endpoint of an authorization server that has been configured once to trust that platform. Each agent gets its own identity and audit trail, without an administrator registering every agent as an OAuth client and without agents sharing one credential.</p>
    <p>WAG defines no new grant type, token format, or registry entries. It covers workloads acting on their own behalf. Acting on behalf of a user is out of scope, though the grant is designed to compose with delegation. WAG is an individual draft and an early exploration, not yet an OAuth Working Group item.</p>

    <img src="wag-flow-diagram.svg" style="width: 100%" alt="Workload Authorization Grant flow: the Platform is registered once at the Authorization Server, the Platform issues a WAG to the Agent, the Agent exchanges it for an access token, and calls the Resource Server">

    <h3>Terminology</h3>

    <dl class="terminology">
      <dt>Workload Authorization Grant <span class="term-abbr">(WAG)</span></dt>
      <dd>The name of both the specification and the JWT it defines. The WAG is signed by the Platform, names one Agent in its <code>sub</code> claim, and is audienced to the Authorization Server's issuer identifier. It can include a <code>scope</code> claim, but the Authorization Server decides what access to grant.</dd>

      <dt>Platform</dt>
      <dd>The system that creates and hosts Agents and signs WAGs about them. The Platform is the issuer of the WAG. A multi-tenant provider is treated as a separate Platform for each customer, ideally with its own issuer identifier.</dd>

      <dt>Agent</dt>
      <dd>A workload running on the Platform, such as an AI agent, that presents a WAG to obtain access tokens. Its Agent Identifier, the <code>sub</code> of the WAG, is opaque, stable, and unique within the Platform.</dd>

      <dt>Platform Registration</dt>
      <dd>The one-time configuration at the Authorization Server that establishes trust in a Platform: its issuer identifier, its signing keys (a JWK Set, a JWK Set URL, or the <code>jwks_uri</code> from its metadata), an optional claim that identifies a tenant, and the local policy that maps the Platform's claims to permissions. It is not an OAuth client registration and issues no client ID.</dd>

      <dt>Authorization Server</dt>
      <dd>The OAuth authorization server that protects the Resource Server. It matches each WAG to exactly one Platform Registration, verifies it with that Platform's keys, applies its own policy, and issues an access token. It does not issue refresh tokens for this grant.</dd>

      <dt>Resource Server</dt>
      <dd>The API the Agent wants to call. It accepts access tokens issued by the Authorization Server.</dd>

      <dt>JWT Authorization Grant <span class="term-abbr">(RFC 7523)</span></dt>
      <dd>An OAuth mechanism for using a signed JWT as an authorization grant at a token endpoint. The Agent sends the WAG with <code>grant_type=urn:ietf:params:oauth:grant-type:jwt-bearer</code> in the <code>assertion</code> parameter, and should name the Resource Server with the <code>resource</code> parameter (<a href="https://datatracker.ietf.org/doc/html/rfc8707">RFC 8707</a>). No client authentication is required.</dd>

      <dt>Just-in-Time Agents</dt>
      <dd>The Authorization Server accepts Agent Identifiers it has never seen before, as long as the WAG comes from a trusted Platform. New Agents need no action at the Authorization Server, so an Authorization Server can limit how many new Agents it accepts per Platform.</dd>
    </dl>

    <br><br>

    <h3>Implementations</h3>

    <div class="grid">
      <div class="column">
        <b>Libraries</b>
        <ul>
          <li><a href="https://github.com/jralmaraz/wimse-identity-fabric">WIMSE Identity Fabric</a> - Platform issuer, Platform Registration, and an in-memory Authorization Server (Go)</li>
        </ul>
      </div>
    </div>

    <h3>Related Specs</h3>
    <ul>
      <li><a href="https://datatracker.ietf.org/doc/html/rfc7523">JWT Profile for OAuth 2.0 Client Authentication and Authorization Grants</a> - the grant that the Workload Authorization Grant profiles</li>
      <li><a href="https://datatracker.ietf.org/doc/html/rfc7521">Assertion Framework for OAuth 2.0 Client Authentication and Authorization Grants</a> - the framework RFC 7523 builds on</li>
      <li><a href="https://datatracker.ietf.org/doc/draft-ietf-oauth-rfc7523bis/">Updates to OAuth 2.0 JSON Web Token (JWT) Client Authentication and Assertion-Based Authorization Grants</a> - updates the audience rules for JWT assertions</li>
      <li><a href="/cross-app-access/">Cross-App Access (XAA)</a> - the Identity Assertion JWT Authorization Grant, which WAG borrows its <code>aud</code>, <code>scope</code>, and <code>tenant</code> claims from</li>
      <li><a href="https://datatracker.ietf.org/doc/html/rfc8707">Resource Indicators for OAuth 2.0 (RFC 8707)</a> - used to name the Resource Server in the token request</li>
      <li><a href="https://github.com/pcarleton/draft-carleton-workload-authz-grant">Workload Authorization Grant on GitHub</a> - the draft's source and issue tracker</li>
    </ul>

    <h3>Discussion</h3>
    <ul>
      <li><a href="https://mailarchive.ietf.org/arch/browse/oauth/?q=draft-carleton-workload-authz-grant">OAuth Working Group mailing list</a> - announcement and discussion of the draft</li>
    </ul>

  </div>
</div>

<?php require('../../includes/_footer.php'); ?>
