# Wisdm

## Introduction
### URL
The WISDM API for the production WISDM instance has a base URL of:

https://wisdm.wirelesscoverage.com/api
### Authentication
The WISDM API uses API keys to authenticate requests, To use your API key, input the string of characters under the X-API-Key header on every HTTP request.

API requests without authentication will fail.

### Errors
WISDM uses conventional HTTP response codes to indicate the success or failure of an API request.

In general: codes in the 2xx range indicate success. Codes in the 4xx range indicate an error that failed given the information provided (e.g. a required parameter was omitted). Codes in the 5xx range indicate an error with WISDM’s servers.

// 200 - OK
Everything worked as expected.

// 400 - Bad Request
The request was malformed, often due to missing a required parameter.

// 403 - Forbidden
The API key lacks sufficient permissions to perform the request.

// 404 - Not Found
The requested resource does not exist.

// 500, 502, 503, 504 - Server Errors
Something went wrong on WISDM's end.
## Availability
### GET /availability/{id}/postcode-lookup
This API has the parameter postcode, which can be a postcode in any format, which is looked up against the list of properties. To use the end point by a third party, an api key must be created with a role of availability_checker_map

Returns a list of objects containing property data that matches the given postcode.

Examples:
// GET /availability/123/postcode-lookup?postcode=sw1a1aa

// 200 - OK
[
    {
        "address": "BUCKINGHAM PALACE\nLONDON",
        "location": {
            "lat": 123.0,
            "lng": 456.0
        },
        "postcode": "SW1A 1AA",
        "ref": "12345"
    }
]
// GET /availability/123/postcode-lookup?postcode=sw1a1aa
// (availability checker has no property set)

// 400 - Bad Request
{
    "error": "no_property_set"
}
// GET /availability/123/postcode-lookup?postcode=123

// 400 - Bad Request
{
    "error": "bad_postcode"
}
### GET /availability/{id}/check
This API has the parameters latitude and longitude, and is used to determine whether a specific property has line-of-sight to any radios. This API also accepts the additional parameter of height, to override the default height (in metres) of the client point.

Returns a list of objects containing radios along with their possible RSL in dBm or null in the case of no signal or error, and result, which is a string containing either pass,failure or error.

Examples:
// GET /availability/123/check?latitude=54.11588&longitude=-1.4739

// 200 - OK
[
    {
        "radio": {
            "bearing": 0.0,
            "eirp": 36.0,
            "frequency": 5.7e3,
            "height": 10.0,
            "id": 255,
            "name": "Site 10 Omni",
            "auto_facing": false,
            "is_access_point": true,
            "site": {
                "height": 10.0,
                "id": 320,
                "location": {
                    "lat": 54.116,
                    "lng": -1.474
                },
                "name": "Site 10",
                "network_id": 43,
                "notes": "",
                "icon": "broadcast_tower"
            }
        },
        "confidence": 1,
        "result": "pass",
        "rsl": -65.659,
        "total_confidence": 5
    }
]
// GET /availability/123/check?latitude=54.11588&longitude=-1.4739&height=50

// 200 - OK
[
    {
        "radio": {
            "bearing": 0.0,
            "eirp": 36.0,
            "frequency": 5.7e3,
            "height": 10.0,
            "id": 255,
            "name": "Site 10 Omni",
            "auto_facing": false,
            "is_access_point": true,
            "site": {
                "height": 10.0,
                "id": 320,
                "location": {
                    "lat": 54.116,
                    "lng": -1.474
                },
                "name": "Site 10",
                "network_id": 43,
                "notes": "",
                "icon": "broadcast_tower"
            }
        },
        "confidence": 0,
        "result": "pass",
        "rsl": -65.659,
        "total_confidence": 5
    }
]
// GET /availability/123/check?latitude=54.11588&longitude=-1.4739&height=0

// 200 - OK
[
    {
        "radio": {
            "bearing": 0.0,
            "eirp": 36.0,
            "frequency": 5.7e3,
            "height": 10.0,
            "id": 255,
            "name": "Site 10 Omni",
            "auto_facing": false,
            "is_access_point": true,
            "site": {
                "height": 10.0,
                "id": 320,
                "location": {
                    "lat": 54.1156,
                    "lng": -1.474
                },
                "name": "Site 10",
                "network_id": 43
            }
        },
        "result": "failure",
        "rsl": null,
        "confidence": 0,
        "total_confidence": 5
    }
]
### GET /availability/check
This API is used to determine whether a specific property has line-of-sight to any access_point. Returns a list of objects containing access points along with line-of-sight test result.

Parameters:
height (Number): Height of the radio.
latitude (Number): Latitude of the radio.
longitude (Number): Longitude of the radio.
los_model_id (Number): ID of the LoS model.
network_view_id (Number): ID of the network view.
property_set_id (Number): ID of the property set.
Examples:
// GET /availability/check?height=10&latitude=53.9&longitude=-1.0&los_model_id=1&network_view_id=2&property_set_id=2

// 200 - OK
[
    {
        "access_point": {
            "antenna_id": 1,
            "auto_facing": false,
            "bearing": 315,
            "eirp": 36,
            "frequency": 5800,
            "height": 10,
            "id": 2,
            "is_access_point": true,
            "name": "New Site 2 Omni",
            "site": {
                "height": 50,
                "icon": "broadcast_tower",
                "id": 2,
                "location": {
                "lat": 53.958685976316104,
                "lng": -1.1004869643185202
                },
                "name": "New Site 2",
                "network_id": 1,
                "notes": ""
            },
            "tilt": 0
        },
        "confidence": 2,
        "result": "pass",
        "rsl": -67.9021987915039,
        "total_confidence": 5
    },
    {
        "access_point": {
            "antenna_id": 1,
            "auto_facing": false,
            "bearing": 15,
            "eirp": 36,
            "frequency": 5800,
            "height": 10,
            "id": 5,
            "is_access_point": true,
            "name": "New Site 3 Omni",
            "site": {
                "height": 10,
                "icon": "broadcast_tower",
                "id": 3,
                "location": {
                "lat": 53.9613655255871,
                "lng": -1.0995627756912256
                },
                "name": "New Site 3",
                "network_id": 1,
                "notes": ""
            },
            "tilt": 0
        },
        "confidence": 0,
        "result": "failure",
        "rsl": null,
        "total_confidence": 5
    }
]
### GET /availability
This API returns a list of availability checkers specifications.

Examples:
// GET /availability

// 200 - OK
[
    {
        "completion_url": null,
        "good_sites_threshold": 1,
        "id": 1,
        "los_model_id": 2,
        "name": "test",
        "network_view_id": 2,
        "property_set_id": 2
    }
]
### POST /availability
This API creates a new availability checker specification.

Parameters:
name (String): Name of the new availability checker.
network_view_id (Number): ID of the network view.
property_set_id (Number): ID of the property set.
los_model_id (Number): ID of the LoS model.
good_sites_threshold (Number): Number of sites required to return a pass with this availability checker.
completion_url (String): URL to provide in the results upon completion of a job.
Example
// POST /availability
{
    "name": "test",
    "network_view_id": 2,
    "property_set_id": 2,
    "los_model_id": 2,
    "good_sites_threshold": 1,
    "completion_url": ""
}

// 200

{
    "completion_url": null,
    "good_sites_threshold": 1,
    "id": 2,
    "los_model_id": 2,
    "name": "test",
    "network_view_id": 2,
    "property_set_id": 2
}
### PATCH /availability/{id}
This API updates the given availability checker.

Example
// PATCH /availability/2
{
    "network_view_id": 6,
}

// 200

{
    "completion_url": null,
    "good_sites_threshold": 1,
    "id": 2,
    "los_model_id": 2,
    "name": "test",
    "network_view_id": 6,
    "property_set_id": 2
}
### DELETE /availability/{id}
This API deletes an availability checker.

Example:
// DELETE /availability/1

// 200 - OK
{}
### GET /availability/{id}/availability-checker-map
This API takes latitude and longitude coordinates and reports about coverage at a given property, returning a cut-down set of results. To use this endpoint from a third-party domain, such as our “Availibity Checker Map” tool, an API key may be created with only the role of availability_checker_map.

https://availability-checker-map.wirelesscoverage.com/?api_key=ABCD1234&availability_checker_id=1
Parameters:
latitude (Number): Latitude of the property.
longitude (Number): Longitude of the property.
Examples:
// GET /availability/8/availability-checker-map?latitude=53.9&longitude=-2.0

// 200 - OK
{
    "completion_url": "https://www.wirelesscoverage.com",
    "good_coverage": false,
    "location": {
        "lat": 53.9,
        "lon": -2.0
    },
    "number_of_connections": 0
}
## Properties
### GET /properties/property-sets/{id}/postcode-lookup
This API has the parameter postcode, which can be a postcode in any format, which is looked up against the list of properties.

Returns a list of properties which match the given postcode.

Examples:
// GET /properties/property-sets/123/postcode-lookup?postcode=sw1a1aa

// 200 - OK
[
    {
        "address": "BUCKINGHAM PALACE\nLONDON",
        "location": {
            "lat": 123.0,
            "lng": 456.0
        },
        "postcode": "SW1A 1AA",
        "ref": "12345"
    }
]
### GET /properties/property-sets/{id}/ap-coverage
This API returns a list of objects containing properties along with their possible RSL in dBm or null in the case of no signal or error; and result, which is a string containing either pass, failure or error.

Parameters:
latitude (Number): Latitude of the access point.
longitude (Number): Longitude of the access point.
eirp (Number): EIRP of the access point.
frequency (Number): Frequency of the access point.
height (Number): Height of the access point.
antenna_id (Number): ID of the access point antenna.
los_model_id (Number): ID of the LoS model.
Optional parameters:

bearing (Number): Bearing of the access point.
tilt (Number): Tilt of the access point.
Examples:
// GET /properties/property-sets/123/ap-coverage
{
    "latitude": 54.11588,
    "longitude": -1.4739,
    "antenna_id": 1,
    "eirp": 36.0,
    "frequency": 400 -0,
    "height": 10,
    "los_model_id": 1,
    "antenna_id": 1
}

// 200 - OK
{
    "pass_count": 2648,
    "total_confidence": 5,
    "results": [
        {
            "rsl": -65.659,
            "confidence": 1,
            "result": "pass",
            "property_id": 1,
            "ref": "12345",
            "coords": "[54.116, -1.474]"
        },
        {
            "rsl": -75.271,
            "confidence": 1,
            "result": "pass",
            "property_id": 1,
            "ref": "12346",
            "coords": "[54.111, -1.512]"
        },
        {
            "rsl": null,
            "confidence": 0,
            "result": "failure",
            "property_id": 1,
            "ref": "12347",
            "coords": "[54.216, -1.479]"
        }
    ]
}
### GET /properties/property-sets/{id}/ap-coverage-report.csv
This API returns an AP coverage report in a CSV format.

Parameters:
latitude (Number): Latitude of the radio.
longitude (Number): Longitude of the radio.
eirp (Number): EIRP of the radio.
frequency (Number): Frequency of the radio.
height (Number): Height of the radio.
antenna_id (Number): ID for the radio antenna.
los_model_id (Number): ID for the LoS model.
Optional parameters:

bearing (Number): Bearing of the radio.
tilt (Number): Tilt of the radio.
pass-only (Boolean): Indicates whether results should be filtered to only pass results.
Examples:
// GET /properties/property-sets/123/ap-coverage-report.csv?latitude=54.11588&longitude=-1.4739&antenna_id=1&eirp=36.0&frequency=4000&height=10&los_model_id=1&antenna_id=1

// 200 - OK

result,rsl,confidence,distance,ref,address,postcode,latitude,longitude
pass,-65.659,1,1000,12345,1 Foo Street,SW1A 1AA,54.116,-1.474
pass,-75.271,1,1000,12346,2 Foo Street,SW1A 1AA,54.111,-1.512
failure,,0,1000,12347,3 Foo Street,SW1A 1AA,54.216,-1.479
GET /properties/property-sets/{id}/ap-optimised-coverage
This API allows a user to select an Antenna and have WISDM recommend the optimum direction and tilt to get maximum Property coverage. It requires sampling coverage in all directions horizontally as well as a given range of tilt in degrees.

Parameters:
antenna_id (Number): ID of the radio antenna.
eirp (Number): EIRP of the radio.
frequency (Number): Frequency of the radio.
height (Number): Height of the radio.
tilt (Number): Tilt of the radio.
bearing (Number): Bearing of the radio.
latitude (Number): Latitude of the radio.
longitude (Number): Longitude of the radio.
los_model_id (Number): ID of the LoS model.
min_tilt (Number): Minimum tilt of the radio.
max_tilt (Number): Maximum tilt of the radio.
Examples:
// GET /properties/property-sets/2/ap-optimised-coverage?antenna_id=2&eirp=36&frequency=5800&height=28&tilt=0&bearing=25&latitude=53.9&longitude=-1.1&los_model_id=1&min_tilt=-7&max_tilt=7

// 200 - OK
{
    "azimuth_division": 72,
    "results": {
        "optimised_azimuth": 240,
        "optimised_tilt": -1,
        "pass_counts": [2293, 2298, ... ]
    },
    "tilt_step": 1
}
### GET /properties/property-sets/{id}for
This API returns a property set.

Examples:
// GET /properties/property-sets/123

// 200 - OK
{
    "description": "foobar",
    "id": "5",
    "name": "North Yorkshire",
    "postcode_format": "uk_postcode",
    "user_fields": [...],
}
### PATCH /properties/property-sets/{id}
This API updates the given property set.

Examples:
// PATCH /properties/property-sets/2

{
    "name": "abp_york test"
}

// 200 - OK
{
    "created_by": null,
    "description": "abp_york",
    "id": 2,
    "inserted_at": "2021-09-01T09:03:38",
    "name": "abp_york",
    "postcode_format": "uk_postcode",
    "property_count": 0,
    "user_fields": [...],
    "user_provided": false
}
### DELETE /properties/property-sets/{id}
This API deletes the given property set.

Example:
// DELETE /properties/property-sets/1

// 200 - OK
{}
### GET /properties/property-sets
This API returns a list of all property sets.

Examples:
// GET /properties/property-sets

// 200 - OK
[
    {
        "created_by": null,
        "description": "foobar",
        "id": 1,
        "inserted_at": "2021-09-01T09:03:38",
        "name": "West Yorkshire",
        "postcode_format": "uk_postcode",
        "property_count": 0,
        "user_fields": [...],
        "user_provided": false
    },
    {
        "created_by": null,
        "description": "foobar",
        "id": 2,
        "inserted_at": "2021-09-01T09:03:38",
        "name": "Greater Manchester",
        "postcode_format": "uk_postcode",
        "property_count": 0,
        "user_fields": [...],
        "user_provided": false
    }
    {
        "created_by": null,
        "description": "foobar",
        "id": 5,
        "inserted_at": "2021-09-01T09:03:38",
        "name": "North Yorkshire",
        "postcode_format": "uk_postcode",
        "property_count": 0,
        "user_fields": [...],
        "user_provided": false
    }
]
### GET /properties/{id}
This API returns a property.

Examples:
// GET /properties/123

// 200 - OK
{
    "address": "BUCKINGHAM PALACE\nLONDON",
    "location": {
        "lat": 123.0,
        "lng": 456.0
    },
    "postcode": "SW1A 1AA",
    "ref": "12345",
    "user_data": {}
}
### POST /properties/property-sets
This API imports new properties data from given csv file. CSV files should be UTF-8 encoded and should contain, as a minimum, latitude and longitude columns.

Parameters:
name (String): Name of the new property set
description (String): Description of the new property set
lat_field (String): Latitude field name
lng_field (String): Longitude field name
ref_field (String): UPRN field name
address_field (String): Address field name
postcode_field (String): Postcode field name
postcode_format (String): Postcode format
Examples:
// POST /properties/property-sets?name=test&description=test+import&lat_field=latitude&lng_field=longitude&ref_field=uprn&address_field=addressbase_postal&postcode_field=postcode&postcode_format=uk_postcode
Content-Disposition: form-data; name="file"; filename="abp_york.csv"
Content-Type: text/csv

// 200 - OK
{
    "created_by": "foo",
    "description": "test import",
    "id": 6,
    "inserted_at": "2022-01-12T10:44:07",
    "name": "test",
    "postcode_format": "uk_postcode",
    "property_count": 0,
    "user_fields": [...],
    "user_provided": true
}
## Account
### GET /auth/editable-roles
This API returns a list of assignable user roles.

Examples:
// GET /auth/editable-roles

// 200 - OK
[
    "availability_checker_map",
    "read",
    "write",
    "admin"
]
### GET /auth/user/roles
This API returns a list of roles available to the current user.

Examples:
// GET /auth/user/roles

// 200 - OK
[
    "ap_coverage",
    "availability_checker_map",
    "postcode_lookup",
    "read",
    "read_antennas",
    "read_elevation_models",
    "read_los_models",
    "read_network_views",
    "read_networks",
    "read_properties",
    "write",
    "write_antennas",
    "write_los_models",
    "write_network_views",
    "write_networks",
    "write_properties",
]
### GET /auth/users
This API returns a list of all users within the organization.

Examples:
// GET /auth/users

// 200 - OK
[
    {
        "email": "user@wirelesscoverage.com",
        "enabled": true,
        "id": 1,
        "realName": "user",
        "roles": ["admin"]
    },
    {
        "email": "user2@wirelesscoverage.com",
        "enabled": true,
        "id": 2,
        "realName": "user",
        "roles": ["write"]
    }
]
### GET /auth/user
This API is used to return the current user’s details.

Examples:
// GET /auth/user

// 200 - OK
{
    "email": "user1@wirelesscoverage.com",
    "enabled": true,
    "id": 1,
    "realName": "Test User",
    "roles": [
        "read",
        "write",
        "admin",
        "superuser"
    ]
}

### PATCH /auth/user
This API updates a user’s personal information.

Parameters:
email (String): Email of the user’s account.
realName (String): Name of the user’s account.
Examples:
// PATCH /auth/user
{
    "realName": "Test User",
    "email": "user1@wirelesscoverage.com"
}

// 200 - OK
### PATCH /auth/users/{id}
This API updates a user’s account details.

Parameters:
email (String): Email of the users account.
enabled (Boolean): Status of the users account.
realName (String): Name of the users account.
roles (List of Strings): Roles of the users account.
Examples:

// PATCH /auth/users/2
{
    "realName": "update name",
    "enabled": "false"
}

// 200 - OK
### DELETE /auth/users/{id}
This API deletes a user’s account.

Examples:
// DELETE /auth/users/2

// 200 - OK
[]
### POST /auth/login
This API logs in as the given user.

Parameters:
login (Object): Login object.
email (String): Email address of user.
password (String): Password of user.
Examples:
// POST /auth/login
{
    "login": {
        "email": "test@test.com",
        "password": "password123"
    }
}

// 200 - OK
{
    "account": {
        "id": 1,
        "organization_name": "Test Org"
    },
    "email": "test@wirelesscoverage.com",
    "enabled": true,
    "id": 1,
    "realName": "test",
    "roles": ["read","write","admin"]
}
### POST /auth/logout
This API logs out a logged in user.

Examples:
// POST /auth/logout

// 200 - OK
[]
## API Keys
### GET /auth/api-keys
This API returns a list of all API keys.

Examples:
// GET /auth/api-keys

// 200 - OK
[
    {
        "enabled": true,
        "id": 1,
        "key": "ABCD1234",
        "name": "testapi",
        "roles": ["read","write"],
    }
]
### POST /auth/api-keys
This API creates a new API key.

Parameters:
name (String): Name of the new API key.
roles (List of String): Roles that belong to the API key.
Examples:
// POST /auth/api-keys
{
    "name": "testapi",
    "roles": ["read","write"]
}

// 201 - Created
{
    "enabled": true,
    "id": 1,
    "key": "ABCD1234",
    "name": "testapi",
    "roles": ["read","write"]
}
### DELETE /auth/api-keys/{id}
This API deletes an API key.

Examples:
// DELETE /auth/api-keys/1

// 200 - OK
{}
### PATCH /auth/api-keys/{id}
This API updates an API key.

Parameters:
enabled (Boolean): Enable / disable API key.
name (String): Name of the API key.
roles (List of String): Roles of the API key.
Examples:
// PATCH /auth/api-keys/1
{
    "name": "test api key"
}

// 200 - OK
{
    "enabled": true,
    "id": 1,
    "key": "62CF997C6C306FB7524C5D5AF1A49FEE57557190AF1A716867395D1CBDEB7BAC",
    "name": "test api key",
    "roles": ["read","write"]
}
## Antennas
### GET /antennas
This API returns a list of all antennas.

Examples:
// GET /antennas

// 200 - OK
[
    {
        "antenna_pattern_id": 1,
        "gain": 12,
        "id": 1,
        "max_frequency": 6000,
        "min_frequency": 2500,
        "name": "Antenna 1"
    },
    {
        "antenna_pattern_id": 1,
        "gain": 15,
        "id": 2,
        "max_frequency": 4200 -,
        "min_frequency": 400 -0,
        "name": "Antenna 2"
    }
]
### GET /antennas/{id}
This API returns an antenna by ID.

Examples:
// GET /antennas/1

// 200 - OK
{
    "antenna_pattern_id": 1,
    "gain": 12,
    "id": 1,
    "max_frequency": 6000,
    "min_frequency": 2500,
    "name": "Antenna 1"
}
### GET /antennas/favourites
This API returns a list of all favourited antennas.

Examples:
// GET /antennas/favourites

// 200 - OK
[
    {
        "antenna_pattern_id": 1,
        "beam_width": 10,
        "gain": 10,
        "id": 1,
        "manufacturer": "cambium",
        "max_frequency": 100000,
        "min_frequency": 0,
        "name": "ePMP1000_SM_atoll"
    }
]
### POST /antennas/{id}/favourite
This API adds the given antenna into the list of favourite antennas.

Examples:
// POST /antennas/1/favourite

// 200 - OK
{}
### DELETE /antennas/{id}/favourite
This API removes the given antenna from the list of favourite antennas.

Examples:
// DELETE /antennas/1/favourite

// 200 - OK
{}
### GET /antennas/{id}/pattern
This API returns an antenna pattern by ID.

Examples:
// GET /antennas/1/pattern

// 200 - OK
{
    "ant_data": [
        0,
        -0.009999999776482582,
        ...
    ],
    "id": 1,
    "name": "ePMP1000_SM_atoll"
}
## LOS Models
### GET /los-models
This API returns a list of all LoS Models.

Examples:
// GET /los-models

// 200 - OK
[
    {
        "backhaul_eirp": 36.0,
        "backhaul_frequency": 5.0e3,
        "backhaul_gain": 23.0,
        "backhaul_min_rsl": -75.0,
        "client_gain": 20.0,
        "client_height": 6.0,
        "elevation_model_id": 1,
        "fresnel_min_clearage": 0.800000011920929,
        "id": 1,
        "max_range": 2.0e4,
        "min_confidence": 1,
        "min_rsl": -75.0,
        "name": "Default Model",
        "radius": 0,
        "use_deep_coverage": false,
        "use_surface_data": true,
        "use_atmosphere_data": false,
    },
    {
        "backhaul_eirp": 36.0,
        "backhaul_frequency": 5.0e3,
        "backhaul_gain": 23.0,
        "backhaul_min_rsl": -75.0,
        "client_gain": 20.0,
        "client_height": 6.0,
        "elevation_model_id": 1,
        "fresnel_min_clearage": 0.800000011920929,
        "id": 2,
        "max_range": 2.0e4,
        "min_confidence": 1,
        "min_rsl": -75.0,
        "name": "Default Model",
        "radius": 3,
        "use_deep_coverage": true,
        "use_surface_data": true,
        "use_atmosphere_data": true,
    }
]
### GET /los-models/{id}
This API returns a LoS Model by ID.

Examples:
// GET /los-models/1

// 200 - OK
{
    "backhaul_eirp": 36,
    "backhaul_frequency": 5700,
    "backhaul_gain": 23,
    "backhaul_min_rsl": -75,
    "client_gain": 20,
    "client_height": 8,
    "elevation_model": {
        "description": "abp_york",
        "id": 1,
        "name": "abp_york"
    },
    "elevation_model_id": 1,
    "fresnel_min_clearage": 0.800000011920929,
    "id": 1,
    "max_range": 20000,
    "min_confidence": 1,
    "min_rsl": -74,
    "name": "New Test Parameters",
    "radius": 3,
    "use_deep_coverage": true,
    "use_surface_data": true,
    "use_atmosphere_data": true,
}
### POST /los-models
This API creates a new LoS Model.

Examples:
// POST /los-models
{
    "name": "New Test",
    "min_rsl": -75,
    "client_gain": 20,
    "client_height": 8,
    "fresnel_min_clearage": 0.8,
    "elevation_model_id": 2,
    "max_range": 20000,
    "backhaul_min_rsl": -75,
    "backhaul_eirp": 36,
    "backhaul_gain": 23,
    "backhaul_frequency": 5700,
    "use_surface_data": true,
    "use_deep_coverage": false,
    "min_confidence": 1,
    "radius": 5,
    "use_atmosphere_data": true,
}

// 200 - OK
{
    "backhaul_eirp": 36,
    "backhaul_frequency": 5700,
    "backhaul_gain": 23,
    "backhaul_min_rsl": -75,
    "client_gain": 20,
    "client_height": 8,
    "elevation_model_id": 2,
    "fresnel_min_clearage": 0.8,
    "id": 6,
    "max_range": 20000,
    "min_confidence": 1,
    "min_rsl": -75,
    "name": "New Test",
    "radius": 5,
    "use_deep_coverage": false,
    "use_surface_data": true,
    "use_atmosphere_data": true,
}
### PATCH /los-models/{id}
This API updates the given LoS Model with new values.

Examples:
// PATCH /los-models/6

{
    "name": "Test"
}

// 200 - OK
{
    "backhaul_eirp": 36,
    "backhaul_frequency": 5700,
    "backhaul_gain": 23,
    "backhaul_min_rsl": -75,
    "client_gain": 20,
    "client_height": 8,
    "elevation_model": {
        "description": "abp_york",
        "id": 1,
        "name": "abp_york"
    },
    "elevation_model_id": 1,
    "fresnel_min_clearage": 0.800000011920929,
    "id": 6,
    "max_range": 20000,
    "min_confidence": 1,
    "min_rsl": -74,
    "name": "Test",
    "radius": 3,
    "use_deep_coverage": true,
    "use_surface_data": true,
    "use_atmosphere_data": true,
}
### DELETE /los-models/{id}
This API deletes the given los model.

Examples:
// DELETE /los-models/6

// 200 - OK
{}
## Elevation Models
### GET /elevation-models
This API returns a list of all Elevation Models.

Examples:
// GET /elevation-models

// 200 - OK
[
    {
        "description": "2 m DSM data for England",
        "id": 1,
        "name": "2 m DSM"
    },
    {
        "description": "1 m DSM data for Ireland",
        "id": 2,
        "name": "Ireland 1 m DSM"
    }
]
### GET /elevation-models/{id}/coverage-area
This API returns the boundary polygon for available data, for a given elevation model. This data is returned as GeoJSON MultiPolygon geometry object.

Examples:
// GET /elevation-models/1/coverage-area

// 200 - OK
{
    "coordinates": [
        [
            [
                [
                    -1.11,
                    53.98
                ],
                ...
            ]
        ]
    ],
    "type": "MultiPolygon"
}
## Networks
### GET /networks
This API returns a list of all networks.

Examples:
// GET /networks

// 200 - OK
[
    {
        "colour": "#E84B3C",
        "id": 1,
        "name": "test network 1"
    },
    {
        "colour": "#E84B3D",
        "id": 2,
        "name": "test network 2"
    }
]
### POST /networks
This API creates a new network.

Parameters:
name (String): Name of the network.
colour (String): Colour of the network in hexadecimal format.
Examples:
// POST /networks
{
    "name": "new network",
    "colour": "#E84B3C"
}

// 201 - Created
{
    "name": "new network",
    "colour": "#E84B3C",
    "id": 2
}
### PATCH /networks/{id}
This API updates a network.

Parameters:
name (String): Name of the network.
colour (String): Colour of the network in hexadecimal format.
Examples:
// PATCH /networks/2
{
    "name": "network name change",
    "colour": "#27AF60"
}

// 200 - OK
{
    "name": "network name change",
    "colour": "#27AF60",
    "id": 2
}
### DELETE /networks/{id}
This API deletes a network.

Examples:
// DELETE /networks/2

// 200 - OK
[]
### POST /networks/import-networks
Imports a CSV file containing a network of sites and radios and creates network(s).

Parameters:
colour (String): Colour of the network in hexadecimal format.
site_name_field (String): Name of the site
network_field (String): Network field name in CSV file
notes_field (String): Notes field name in CSV file
site_height_field (String): Site height field name in CSV file
lat_field (String): Latitude field name in CSV file
lng_field (String): Longitude field name in CSV file
aps_name_field (String): ‘Access point name’ field name in CSV file
aps_site_field (String): ‘Access point site’ field name in CSV file
aps_height_field (String): ‘Access point height’ field name in CSV file
frequency_field (String): Frequency field name in CSV file
eirp_field (String): EIRP field name in CSV file
antenna_id_field (String): Antenna id field name in CSV file
tilt_field (String): Tilt field name in CSV file
bearing_field (String): Bearing field name in CSV file
Examples:
// POST /networks/import-networks?colour=#808080&site_name_field=Site_Name&network_field=Network&notes_field=Notes&site_height_field=Max_Height&lat_field=Latitude&lng_field=Longitude&aps_name_field=AP_Name&aps_site_field=Site_Name&aps_height_field=Installed_Height&frequency_field=Frequency_MHz&eirp_field=EIRP&antenna_id_field=Antenna_ID&tilt_field=Tilt_Deg&bearing_field=Bearing_Deg_FTN
Content-Disposition: form-data; name="sites_file"; filename="sites_template.csv"
Content-Type: text/csv

Content-Disposition: form-data; name="aps_file"; filename="radios_template.csv"
Content-Type: text/csv;

// 200 - OK
[
    {
        "colour": "#808080",
        "id": 5,
        "name": " Template Network"
    }
]
### POST /networks/import-isotropic
Imports a CSV file containing one network of sites. The import_isotropic endpoint creates a network with user given sites and auto populates radio’s given the frequency and the EIRP. The antenna to be given to all sites can be selected, otherwise it will automatically be populated with isotropic antennas.

Parameters:
name (String): Name of the network.
colour (String): Colour of the network in hexadecimal format.
lat_field (String): Latitude field name in CSV file
lng_field (String): Longitude field name in CSV file
site_name_field (String): Site name field name in CSV file
height_field (String): ‘access point height’ field name in CSV file
notes_field (String): Notes field name in CSV file
frequency_field (String): Frequency field name in CSV file
eirp_field (String): EIRP field name in CSV file
antenna_id (Number): ID of antenna to be given to all new sites
Examples:
// POST /networks/import-isotropic?name=simple+csv&colour=#808080&lat_field=Latitude&lng_field=Longitude&site_name_field=Site_Name&height_field=Max_Height&notes_field=Notes&frequency_field=Frequency_MHz&eirp_field=EIRP&antenna_id=1
Content-Disposition: form-data; name="file"; filename="simple_import.csv"
Content-Type: text/csv;

// 200 - OK
{
    "colour": "#808080",
    "id": 6,
    "name": "simple csv"
}
## Network Views
### GET /networks/views
This API returns a list of all network views.

Examples:
// GET /networks/views

// 200 - OK

[
    {
        "id": 1,
        "name": "network name",
        "networks": [1]
    },
    {
        "id": 2,
        "name": "network name 2",
        "networks": [2, 3]
    }
]
### POST /networks/views
This API creates a new network view.

Parameters:
name (String): name of the network view.
networks (List of Numbers): IDs of the networks that belong to the network view.
Examples:
// POST /networks/views
{
    "name": "new network view",
    "networks": [1, 2]
}

// 201 - Created
{
    "name": "new network view",
    "networks": [1, 2],
    "id": 2
}
### PATCH /networks/views/{id}
This API updates a network view.

Parameters:
name (String): name of the network view.
networks (List of Numbers): IDs of the networks that belong to the network view.
Examples:
// PATCH /networks/views/2
{
    "name":"network view name change",
    "networks":[1]
}

// 200 - OK
{
    "name": "network view name change",
    "networks": [1],
    "id": 2
}
### DELETE /networks/views/{id}
This API deletes a network view.

Examples:
// DELETE /networks/views/2

// 200 - OK
[]
### GET /networks/views/{id}/coverage-report.csv
This API returns a CSV of a network views coverage report.

Examples:
// GET /networks/views/2/coverage-report.csv

// 200 - OK
site_name,site_id,ap_name,ap_id,result,rsl,confidence,distance,ref,address,postcode,latitude,longitude
Test Site,1,Test Radio,1,-65.1,pass,1,5000,12345,,,54.116,1.474
Test Site,1,Test Radio,1,-85.1,failure,1,6000,12346,,,54.216,1.574
Test Site,1,Test Radio,1,-65.1,pass,1,4000,12347,,,54.316,1.374
### GET /networks/views/{id}/sites.csv
This API returns a CSV of a all sites in a given network view.

Examples:
// GET /networks/views/2/sites.csv

// 200 - OK
id,name,network,latitude,longitude,height,notes
4,New Site,test network,54.20,-0.37,10.0,
1,New Site,test network,54.22,-0.39,11.0,
### GET /networks/views/{id}/radios.csv
This API returns a CSV of a network views radios.

### GET /networks/views/{id}/backhauls.csv
This API returns a CSV of a backhauls.

## Sites
### GET /networks/{id}/sites
This API returns a list of sites within a network.

Examples:
// GET /networks/43/sites

// 200 - OK
[
    {
        "height": 10.0,
        "id": 320,
        "location": {
            "lat": 54.116,
            "lng": -1.474
        },
        "name": "Site 10",
        "network_id": 1,
        "notes": "",
        "icon": "broadcast_tower"
    },
    {
        "height": 20.0,
        "id": 322,
        "location": {
            "lat": 54.116,
            "lng": -1.474
        },
        "name": "New Site",
        "network_id": 1,
        "notes": "",
        "icon": "broadcast_tower"
    },
]
### POST /networks/{id}/sites
This API creates a new site.

Parameters:
name (String): Name of the site.
radios (List of Objects): Radios that belong to the site.
height (Number): Height of the site.
location (Object): Location of site.
lat (Number): Latitude.
lng (Number): Longitude.
Examples:
// POST /networks/43/sites
{
    "name": "New site",
    "radios": [
        {
            "antenna_id": 1,
            "bearing": 0,
            "eirp": 36,
            "frequency": 5000,
            "height": 10,
            "name": "New radio",
            "tilt": 0,
            "auto_facing": false,
            "is_access_point": true,
        }
    ],
    "height": 15,
    "location": {
        "lat": 54.116,
        "lng": -1.474
    },
    "name": "New Site",
    "icon": "broadcast_tower"
}

// 201 - Created
{
    "name": "New Site",
    "height": 15.0,
    "radios": [
        {
            "antenna_id": 1,
            "bearing": 0,
            "eirp": 36,
            "frequency": 5000,
            "height": 10,
            "name": "New radios",
            "tilt": 0,
            "id": 1,
            "auto_facing": false,
            "is_access_point": true,
        }
    ],
    "location": {
        "lat": 54.116,
        "lng": -1.474
    },
    "name": "New Site",
    "id": 320,
    "network_id": 43,
    "notes": "",
    "icon": "broadcast_tower"
}
### GET /sites/{id}
This API returns a site.

Examples:
// GET /sites/320

// 200 - OK
{
    "height": 10.0,
    "id": 320,
    "location": {
        "lat": 54.116,
        "lng": -1.474
    },
    "name": "Site 10",
    "network_id": 1,
    "notes": "",
    "icon": "broadcast_tower"
}
### PATCH /sites/{id}
This API updates a site.

Parameters:
name (String): Name of the site.
radios (List of Objects): Radios that belong to the site.
height (Number): Height of the site.
location (Object): Location of site.
lat (Number): Latitude.
lng (Number): Longitude.
Examples:
// PATCH /sites/320
{
    "name": "Change site name"
    "height": 15
}

// 200 - OK
{
    "height": 15.0,
    "id": 320,
    "location": {
        "lat": 54.116,
        "lng": -1.474
    },
    "name": "Change site name",
    "network_id": 1,
    "notes": "",
    "icon": "broadcast_tower"
}
### DELETE /sites/{id}
This API deletes a site.

Examples:
// DELETE /sites/320

// 200 - OK
[]
### POST /sites/{id}/clone
This API creates a clone of a given site.

Parameters:
location (Object): Location of new site.
lat (Number): Latitude.
lng (Number): Longitude.
Examples:
// POST /sites/3/clone
location: {lat: 53.96077655335171, lng: -1.1054920341530874}

// 200 - OK

{
    "height": 10,
    "icon": "broadcast_tower",
    "id": 12,
    "location": {
        "lat": 53.96077655335171,
        "lng": -1.1054920341530874
    },
    "name": "New Site 3 Copy",
    "network_id": 1,
    "notes": "",
    "radios": [
        {
        "antenna_id": 1,
        "auto_facing": false,
        "bearing": 15,
        "eirp": 36,
        "frequency": 5800,
        "height": 10,
        "id": 24,
        "is_access_point": true,
        "name": "New Site 3 Omni",
        "tilt": 0
        }
    ]
}
## Site Links
### GET /networks/views/{id}/site-links
This API returns a list of sites that have line-of-sight to a given site.

Parameters:
height (Number): Height of the site.
latitude (Number): Latitude of site.
longitude (Number): Longitude of site.
los_model_id (Integer): ID of LoS model to use.
network_view_id (Number): ID of network view.
Examples:
// GET /networks/views/2/site-links?height=50&latitude=53.958685976316104&longitude=-1.1004869643185202&los_model_id=1&network_view_id=2

// 200 - OK
[
    {
        "height": 10,
        "icon": "broadcast_tower",
        "id": 3,
        "location": {
            "lat": 53.9613655255871,
            "lng": -1.0995627756912256
        },
        "name": "New Site 3",
        "network_id": 1,
        "notes": ""
    },
    {
        "height": 10,
        "icon": "broadcast_tower",
        "id": 4,
        "location": {
            "lat": 53.9,
            "lng": -1.1
        },
        "name": "New Site 4",
        "network_id": 1,
        "notes": ""
    },
    {
        "height": 50,
        "icon": "broadcast_tower",
        "id": 1,
        "location": {
            "lat": 53.95589064616047,
            "lng": -1.0909471630753558
        },
        "name": "New Site 5",
        "network_id": 1,
        "notes": ""
    }
]
## Radios
### GET /sites/{id}/radios
This API returns a list of a radios belonging to a site.

Examples:
// GET /sites/320/radios

// 200 - OK
[
    {
        "antenna_id": 1,
        "bearing": 0,
        "eirp": 36,
        "frequency": 5000,
        "height": 10,
        "name": "Radio 1",
        "tilt": 0,
        "id": 1,
        "auto_facing": false,
        "is_access_point": true
    },
    {
        "antenna_id": 1,
        "bearing": 0,
        "eirp": 36,
        "frequency": 5000,
        "height": 10,
        "name": "Radio 2",
        "tilt": 0,
        "id": 2,
        "auto_facing": false,
        "is_access_point": true
    }
]
### POST /sites/{id}/radios
This API creates a new radio within a site.

Parameters:
antenna_id (Number): Antenna ID.
bearing (Number): Bearing of radio.
eirp (Number): EIRP of radio.
height (Number): Height of radio.
name (String): Name of radio.
tilt (Number): Tilt of radio.
auto_facing (Boolean): Flag to indicate if the radio should automatically face its peer.
is_access_point (Boolean): Flag to indicate if the radio is an access_point.
Examples:
// POST /sites/320/radios
{
    "antenna_id": 2,
    "bearing": 0,
    "eirp": 30,
    "frequency": 400 -0,
    "height": 10,
    "name": "New radio",
    "tilt": 0,
}

// 201 - Created
{
    "antenna_id": 1,
    "bearing": 0,
    "eirp": 30,
    "frequency": 400 -0,
    "height": 10,
    "name": "New radio",
    "tilt": 0,
    "id": 3,
}
### GET /radios/{id}
This API returns a radio by ID.

Examples:
// GET /radio/3

// 200 - OK
{
    "antenna_id": 1,
    "bearing": 0,
    "eirp": 30,
    "frequency": 400 -0,
    "height": 10,
    "name": "New radio",
    "tilt": 0,
    "id": 3
}
### PATCH /radios/{id}
This API updates a radio.

Parameters:
antenna_id (Number): Radio antenna ID.
bearing (Number): Bearing of radio.
eirp (Number): EIRP of radio.
height (Number): Height of radio.
name (String): Name of radio.
tilt (Number): Tilt of radio.
Examples:
// PATCH /radios/3
{
    "antenna_id": 1,
    "eirp": 36,
    "frequency": 6000,
    "name": "Radio 3",
    "tilt": 0,
}

// 200 - OK
{
    "antenna_id": 1,
    "bearing": 0,
    "eirp": 36,
    "frequency": 6000,
    "height": 10,
    "name": "Radio 3",
    "tilt": 0,
    "id": 3,
}
### DELETE /radios/{id}
This API deletes a radio.

Examples:
// DELETE /radios/1

// 200 - OK
[]
## Backhauls
### GET /backhauls
This API returns a list of backhauls

Examples:
// GET /backhauls

// 200 - OK
[
    {
        "id": 1,
        "name": "Backhaul 1",
        "capacity": 0.0,
        "distance": 0.0,
        "notes": "Description for backhaul 1",
        "media_type": "pon",
        "site_a_id": 1,
        "site_b_id": 3
    },
    {
        "id": 2,
        "name": "Backhaul 2",
        "capacity": 0.0,
        "distance": 0.0,
        "notes": "Description for backhaul 2",
        "media_type": "dark_fibre",
        "site_a_id": 4,
        "site_b_id": 6
    }
]
### GET /backhauls/{id}
This API returns a backhaul object with the requested id

Examples:
// GET /backhauls/1

// 200 - OK
    {
        "id": 1,
        "name": "Backhaul 1",
        "capacity": 0.0,
        "distance": 0.0,
        "notes": "Description for backhaul 1",
        "media_type": "pon",
        "site_a_id": 1,
        "site_b_id": 3
    }
### GET /sites/{id}/backhauls/
This API returns a list of backhauls connected to the given site.

Examples:
// GET /sites/1/backhauls/

// 200 - OK
[
    {
        "id": 1,
        "name": "Backhaul 1",
        "capacity": 0.0,
        "distance": 0.0,
        "notes": "Description for backhaul 1",
        "media_type": "pon",
        "site_a_id": 1,
        "site_b_id": 3
    },

    {
        "id": 2,
        "name": "Backhaul 2",
        "capacity": 0.0,
        "distance": 0.0,
        "notes": "Description for backhaul 2",
        "media_type": "dark_fibre",
        "site_a_id": 1,
        "site_b_id": 6
    }
]
### DELETE /backhauls/{id}
This API deletes a backhaul object by id.

Examples:
// DELETE /backhauls/1

// 200 - OK
[]
### POST /backhauls
This API creates a new backhaul.

Parameters:
name (String): Radios name.
capacity (Number): Bandwidth in Mbps.
notes (Number): Notes field of the backhaul.
media_type (String): Media type of backhaul.
site_a_id (Number): Site A ID.
site_b_id (Number): Site B ID.
Optional parameters:

distance (Number): Distance of link in meters, set to null for automatic.
radio_a_id (Number): Radio A ID (in case of “wireless” media type).
radio_b_id (Number): Radio B ID (in case of “wireless” media type).
Examples:
// //backhauls
{
    "name": "Backhaul 1",
    "capacity": 0.0,
    "distance": 0.0,
    "notes": "Description for backhaul 1",
    "media_type": "pon",
    "site_a_id": 1,
    "site_b_id": 3
}

// 201 - Created
{
    "id": 2,
    "name": "Backhaul 2",
    "capacity": 0.0,
    "distance": 0.0,
    "notes": "Description for backhaul 2",
    "media_type": "wireless",
    "site_a_id": 1,
    "site_b_id": 6,
    "radio_a_id": 1,
    "radio_b_id": 5
}
### PATCH /backhauls/{id}
This API updates a backhaul.

Parameters:
name (String): Radios name.
capacity (Number): Bandwidth in Mbps.
notes (Number): Notes field of the backhaul.
media_type (String): Media type of backhaul.
site_a_id (Number): Site A ID.
site_b_id (Number): Site B ID.
Optional parameters:

distance (Number): Distance of link in meters, set to null for automatic.
radio_a_id (Number): Radio A ID (in case of “wireless” media type).
radio_b_id (Number): Radio B ID (in case of “wireless” media type).
Examples:
// PATCH /backhauls/3
{
    "name": "Backhaul 3",
    "capacity": 0.0,
    "distance": 0.0,
    "notes": "Description for backhaul 3",
    "media_type": "pon",
    "site_a_id": 1,
    "site_b_id": 3
}

// 200 - OK
{
    "id": 3,
    "name": "Backhaul 3",
    "capacity": 0.0,
    "distance": 0.0,
    "notes": "Description for backhaul 3",
    "media_type": "pon",
    "site_a_id": 1,
    "site_b_id": 6
}
## Map Data Sources
### GET /map-data-sources
This API returns a list of all map data sources.

Examples:
// GET /map-data-sources

// 200 - OK
[
    {
        "id": 1,
        "name": "Raster Image 1",
        "description": "Source 1 Description",
        "type": "raster_image",
        "layer_styles": null,
        "external_url": null,
        "image_bounds": {"xmin": 1.0, "ymin": 2.0, "xmax": 3.0,
        "ymax": 4.0}
    },
    {
        "id": 2,
        "name": "Vector GeoJSON 2",
        "description": "Source 2 Description",
        "type": "vector_geojson",
        "layer_styles": [{"x": 123, "y": 456}],
        "external_url": null,
        "image_bounds": null
    },
]
### GET /map-data-sources/{id}
This API returns a map data source object by ID.

Examples:
// GET /map-data-sources/1

// 200 - OK
{
    "id": 1,
    "name": "Raster Image 1",
    "description": "Source 1 Description",
    "type": "raster_image",
    "layer_styles": null,
    "external_url": null,
    "image_bounds": {"xmin": 1.0, "ymin": 2.0, "xmax": 3.0,
    "ymax": 4.0}
}
### PATCH /map-data-sources/{id}
This API updates a map data source.

Parameters:
name (String): Map Data Source name.
description (String): Map Data Source description.
Examples:
// PATCH /map-data-sources/2
{
    "name": "Updated Vector GeoJSON",
    "description": "Updated Source 2 Description",
}

// 200 - OK
{
    "id": 2,
    "name": "Updated Vector GeoJSON",
    "description": "Updated Source 2 Description",
    "type": "vector_geojson",
    "layer_styles": [{"x": 123, "y": 456}],
    "external_url": null,
    "image_bounds": null
}
### GET /map-data-sources/{id}/raster-image
This API returns the PNG image data from a map data source of “raster_image” type.

Examples:
// GET /map-data-sources/1/raster-image

// 200 - OK
## Binary PNG data
### GET /map-data-sources/{id}/vector-geojson
This API returns the GeoJSON data from a map data source of “vector_geojson” type.

Examples:
// GET /map-data-sources/2/vector-geojson

// 200 - OK
GeoJSON Feature Object
### POST /map-data-sources/vector-geojson/csv
This API imports a CSV to create a “vector_geojson” layer of points, from each row of the CSV.

Examples:
// POST map-data-sources/vector-geojson/csv?name=Example+Import&description=Example&colour=#808080&lat_field=Latitude&lng_field=Longitude&is_heatmap=false

// 200 - OK
{
    "id": 162,
    "name": "Example Import",
    "description": "Example",
    "geojson_data": {"featureCollection"},
    "layer_styles": "mapbox layerstyle object"
}
### DELETE /map-data-sources/{id}
This API deletes a map data source from the account.

Examples:
// DELETE /map-data-sources/2

// 200 - OK
[]
## Deep Coverage
### GET /deep-coverage-points/points
This API checks multiple points at each property and returns a list of LoS results at different parts of property.

Examples:
// GET /deep-coverage-points/points?los_model_id=1&latitude_a=53.9&longitude_a=-1.1&height_a=28&azimuth_a=25&tilt_a=0&eirp_a=36&antenna_id_a=2&latitude_b=53.9456652&longitude_b=-1.0927259&height_b=8&gain_b=20&frequency=5800&autofacing_a=false&autofacing_b=true

// 200 - OK
[
    {
        "confidence": 0,
        "offset_point": [0,0],
        "result": "failure",
        "rsl": null
    },
    {
        "confidence": 0,
        "offset_point": [3,0],
        "result": "failure",
        "rsl": null
    },
    {
        "confidence": 0,
        "offset_point": [0,-3],
        "result": "failure",
        "rsl": -74.02085876464844
    },
    {
        "confidence": 1,
        "offset_point": [-3,0],
        "result": "pass",
        "rsl": -73.94241333007812
    },
    {
        "confidence": 0,
        "offset_point": [0,3],
        "result": "failure",
        "rsl": null
    }
]
## Path Profile
### GET /path-profile
This API returns a path profile.

Parameters:
los_model_id (Integer): ID of LoS model to use.
latitude_a (Integer): Latitude of access point.
longitude_a (Integer): Longitude of access point.
height_a (Integer): Height of access point.
azimuth_a (Integer): Azimuth of access point.
tilt_a (Integer): Tilt of access point.
eirp_a (Integer): EIRP of access point.
antenna_id_a (Integer): Antenna ID
latitude_b (Integer): Latitude of property.
longitude_b (Integer): Longitude of property.
height_b (Integer): Height of radio at property.
gain_b (Integer): Gain of the property antenna in dBi.
frequency (Integer): Frequency of the radio link in MHz.
autofacing_a (Boolean): Whether the access point radio is assumed to be facing directly towards its peer.
autofacing_b (Boolean): Whether the property radio is assumed to be facing directly towards its peer.
use_deep_coverage (Boolean): Whether LoS test will be performed on multiple points of a property.
Examples:
// GET /path-profile?los_model_id=1&latitude_a=53.9&longitude_a=-1.1&height_a=28&azimuth_a=25&tilt_a=0&eirp_a=36&antenna_id_a=2&latitude_b=53.9&longitude_b=-1.1&height_b=8&gain_b=20&frequency=5800&autofacing_a=false&autofacing_b=true&use_deep_coverage=true
{
    "appraisal": {
        "result": "pass",
        "rsl": -73.90847778320312
    },
    "direct_samples": [
        41.573272705078125,
        41.518836975097656,
        ...
    ],
    "earth_samples": [
        0,
        0.00002258240601804573,
        ...
    ],
    "flat_distance": 384.8049011230469,
    "fresnel_samples": [
        41.55576705932617,
        41.3350715637207,
        ...
    ],
    "ground_samples": [
        13.573273658752441,
        13.603753089904785,
        ...
    ],
    "sample_count": 384,
    "sample_types": [
        0,
        0,
        ...
    ],
    "surface_samples": [
        13.569296836853027,
        13.546101570129395,
        ...
    ]
}
## Viewsheds
### GET /viewsheds/single/wms
This API returns a viewshed image in requested image format. This WMS (Web Map Service) endpoint only returns image in requested format. It does not support any other requests. Only WMS version 1.3 is supported.

Please refer to: WMS specifications.

Parameters:
bbox (Integer): Bounding box corners (lower left, upper right). Values must be in units of the specified SRS.
format (String): Requested image format. Currently only “image/png” is supported.
service (String): Specify “WMS” here.
version (Integer): Specify “1.3.0” here.
request (String): Request name. Currently on “GetMap” is supported.
srs (String): Target projection. “3857” is recommended here for web map usage.
width (Integer): Width in pixels of resulting map image.
height (Integer): Height in pixels of resulting map image.
layers (Integer): Specify “viewshed” here.
styles (Integer): Specify “viewshed” here.
bearing (Integer): Bearing of access point.
tilt (Integer): Tilt of access point.
los_model_id (Integer): ID of LoS model to use.
ap_frequency (Integer): Frequency of access point in MHz.
ap_eirp_a (Integer): EIRP of access point.
ap_height_a (Integer): Height of access point.
ap_latitude_a (Integer): Latitude of access point.
ap_longitude_a (Integer): Longitude of access point.
ap_antenna_id_a (Integer): Antenna ID of access point
rsl_stop_colours[] (List of Integer): List of colours representing RSL for each gradient stop, in hexadecimal RGBA format.
rsl_stop_values[] (List of Integer): List of RSL values for each gradient stop, in dBm.
Examples:
// GET /viewsheds/single/wms?bbox=-122299.2,7156951.8,-117407.2,7161843.8&format=image/png&service=WMS&version=1.3.0&request=GetMap&srs=EPSG:3857&width=512&height=512&layers=viewshed&styles=viewshed&bearing=25&tilt=0&los_model_id=1&ap_frequency=5800&ap_eirp=36&ap_height=28&ap_latitude=53.9&ap_longitude=-1.1&ap_antenna_id=2&rsl_stop_colours[]=#AF4FAFBF&rsl_stop_colours[]=#4F4FAFBF&rsl_stop_colours[]=#4FAF4FBF&rsl_stop_colours[]=#AFAF4FBF&rsl_stop_colours[]=#AF4F4FBF&rsl_stop_values[]=-78&rsl_stop_values[]=-75&rsl_stop_values[]=-70&rsl_stop_values[]=-60&rsl_stop_values[]=-40
## Elevation data
### GET /elevation-data
This API has the parameters latitude and longitude, and is used to fetch elevation data. Its returns ground height above sea level and surface height above ground level in meters.

Parameters:
latitude (Number): Latitude of the location.
longitude (Number): Longitude of the location.
los_model_id (Number): ID of the LoS model.
Examples:
// GET /elevation-data?latitude=53.9&longitude=-1.0&los_model_id=6
{
    "ground_sample": 13.71,
    "surface_sample": 10.64
}
## Reports
### POST /reports/engineer
This API returns a PDF report for a single property. The report contains information about the property and access point, as well as a deep coverage check.

Parameters:
los_model_id (Integer): ID of LoS model to use.
latitude_a (Integer): Latitude of property.
longitude_a (Integer): Longitude of property.
height_a (Integer): Height of radio at property.
azimuth_a (Integer): Azimuth of radio at property.
tilt_a (Integer): Tilt of radio at property.
eirp_a (Integer): EIRP of radio at property.
antenna_id_a (Integer): Antenna ID.
latitude_b (Integer): Latitude of access point.
longitude_b (Integer): Longitude of access point.
height_b (Integer): Height of access point.
azimuth_b (Integer): Azimuth of access point.
tilt_b (Integer): Tilt of access point.
gain_b (Integer): Gain of the access point antenna in dBi.
antenna_id_b (Integer): Antenna ID.
frequency (Integer): Frequency of the radio link in MHz.
autofacing_a (Boolean): Whether the property radio is assumed to be facing directly towards its peer.
autofacing_b (Boolean): Whether the access point radio is assumed to be facing directly towards its peer.
property_id (Integer): Property ID.
site_id (Integer): Site ID.
radio_id (Integer): Radio ID.
Examples:
// POST /reports/engineer

{
    "los_model_id": 1,
    "latitude_a": 53.877940,
    "longitude_a": -0.953692,
    "height_a": 10,
    "azimuth_a": 25,
    "tilt_a": 0,
    "eirp_a": 36,
    "antenna_id_a": 1,
    "latitude_b": 56.877940,
    "longitude_b": -2.953692,
    "height_b": 10,
    "azimuth_b": 34,
    "tilt_b": 0,
    "gain_b": 30,
    "antenna_id_b": 3,
    "frequency": 5600,
    "autofacing_a": true,
    "autofacing_b": true,
    "property_id": 3,
    "site_id": 1,
    "radio_id": 1
}

// 201 - CREATED
## Binary PDF File
### POST /reports/backhaul
This API returns a PDF report for a single backhaul. The report contains information both sites and the backhaul, as well as a path profile.

Parameters:
backhaul_id (Integer): ID of backhaul to use
los_model_id (Integer): ID of LoS model to use
Examples:
// POST /reports/backhaul

{
    "backhaul_id": 1,
    "los_model_id": 6
}

// 201 - CREATED
Binary PDF File
