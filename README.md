# MinimalGraphAPI
Minimalistic Version of the Facebook Graph API SDK

## Installation
Just include it in your PHP Script

## Usage
```php
// Create a new API Object and initialize it with your App Details
$fb=new AlwokeFB("AppID","AppSecret");

// For doing something that requires Permissions, you can pass a User or Pagetoken into the API
$fb->SetToken("someFacebookToken");

// For querying the API you do this: (It decodes the JSON and gives you an Object. See examples below...)
$response=$fb->API("/v2.11/me");
```

## Examples
#### Getting the last 5 Posts from a Page
```php
$fb=new AlwokeFB("AppID","AppSecret");
$fb->SetToken("someFacebookToken");

// Query the API
$feed=$fb->API("/v2.11/SOME_PAGE_ID/feed?fields=id,created_time,message&limit=5");

// Iterate over Results and print Info
foreach ($feed->data as $post)
{
  echo $post->id;
  echo $post->message;
}
```

#### Get some Image from the "Timeline Album"
```php
$fb=new AlwokeFB("AppID","AppSecret");
$fb->SetToken("someFacebookToken");

// Query the API
$albums=$fb->API("/v2.11/SOME_PAGE_ID/albums");

// Iterate over Results and print Info
foreach ($albums->data as $album)
{
  if ($album->name="Timeline Photos")
  {
    $photos=$fb->API("/v2.11/".$album->id."/photos?fields=name,images");
    foreach ($photos as $photo)
    {
      echo $photo->name;
      echo $photo->images[0]->source;
    }
    break;
  }
}
```
