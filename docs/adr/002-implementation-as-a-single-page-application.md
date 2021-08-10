# 1. Implementation as a single page application

Date: 2021-08-10

## Status

Proposed

## Context

[Single-page applications (SPA's)](https://en.wikipedia.org/wiki/Single-page_application) have many advantages. The most notable reasons for this application are the speed and UX enhancements. Big downsides however are the increased complexity and our team's inexperience with this technology. All features and requirements can be met with a more traditional implementation, where every navigation to a new page would trigger a full page reload.

## Decision

In order to keep the first version of this application simple, we will not complicate the development with such a technology.

However, for version 2.0 of this application we would use [Vue.js](https://vuejs.org/) as our front-end framework. For the back-end, we will expose an API to connect with the front-end. This API can easily be implemented with [API Platform](https://api-platform.com/) because of its excellent integration with Symfony and Doctrine. We could probably use [API Platform Admin](https://api-platform.com/docs/admin/) as an admin environment, but further implementation details will be left for a later time.

That being said, it must be stated that Vue is designed from the ground up to be incrementally adoptable. This will benefit our effort by enabling us to slowly incorporate functionality where it is most needed, improving both the application and our skills with the technology at a more graceful pace.

## Consequences

Because of this decision we can prototype and deliver a fully working application much faster. In addition, it will give us a longer runway to get accustomed to Vue.

However, it is anticipated that not all work done related with the traditional approach would be transferable. Therefore, this is increasing the total workload and putting the implementation as an SPA further down the road.
