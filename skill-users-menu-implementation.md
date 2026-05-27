# Skill Implementation: Filament Top Navigation Clusters

This document details the concepts and behaviors observed when implementing a "Top Navigation" (Tabs) menu structure using Filament Clusters.

## Core Concepts

### 1. Clusters and Sub-Navigation
In Filament v3, Clusters are used to group related resources under a single sidebar item. By default, Clusters use a side sub-navigation. By setting `$subNavigationPosition = SubNavigationPosition::Top`, the sub-navigation items are rendered horizontally as top tabs.

### 2. Tab Generation via Navigation Groups
When a Cluster is configured to use top navigation, Filament organizes the resources into tabs based on their **Navigation Group**:
- Resources with the *same* `$navigationGroup` are grouped together.
- If a group contains multiple resources, it renders as a single tab that reveals a dropdown when clicked.
- If a resource is assigned to a group (even if it's the only one), Filament may still render it as a dropdown inside a Cluster.
- **Key Insight:** To create a flat, standalone tab without a dropdown, simply *remove or omit* the `$navigationGroup` property from the resource.

### 3. Default Routing & Sorting
When a user clicks on the Cluster link in the main sidebar, Filament automatically redirects to the first resource within that cluster.
- The order of tabs is determined by the `$navigationSort` property on the individual resources.
- **Ungrouped vs Grouped:** Resources without a `$navigationGroup` (flat tabs) naturally sort *before* grouped items.
- Setting `$navigationSort = 1` on the `UserResource` without a group ensures it appears first, thereby making the "Users" list the default page loaded when the cluster is accessed, and places its tab neatly to the left of grouped tabs like "Configuration".

### 4. PHP 8.1+ Property Type Strictness
When overriding properties like `$navigationGroup` in a child Resource class, PHP enforces strict type compatibility. Since Filament's base `Resource` class defines this property as a union type, your resource must match it exactly:
```php
protected static \UnitEnum|string|null $navigationGroup = 'Users';
```
Using `?string` will trigger a fatal `TypeError` in PHP 8.2.
