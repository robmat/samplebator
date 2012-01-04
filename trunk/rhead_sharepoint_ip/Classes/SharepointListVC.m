#import "SharepointListVC.h"
#import "SharepointListTVC.h"
#import "Base64.h"
#import "rhead_sharepoint_ipAppDelegate.h"
#import <ImageIO/ImageIO.h>

@implementation SharepointListVC

@synthesize tableView, sltvc, listsData, myListName, titleStr, currentFolder, map;

- (void)viewDidLoad {
    [super viewDidLoad];
	sltvc = [[SharepointListTVC alloc] initWithStyle:UITableViewStylePlain];
	sltvc.listsData = listsData;
	sltvc.tableView = tableView;
	sltvc.navCntrl = self.navigationController;
	sltvc.myListName = myListName;
	sltvc.delegate = self;
	sltvc.currentFolder = currentFolder;
    if ([currentFolder rangeOfString:@"Pictures"].location == 0) {
        UIBarButtonItem* barBtnItem = [[UIBarButtonItem alloc] initWithImage:[UIImage imageNamed:@"upload_photo_buton.png"] style:UIBarButtonItemStyleBordered target:self action:@selector(uploadPhoto:)];
        self.navigationItem.rightBarButtonItem = barBtnItem;
    }
    sltvc.tableView.backgroundColor = [UIColor clearColor];
	[sltvc viewDidLoad];
	backBtn.hidden = YES;
	dateFrmt = [[NSDateFormatter alloc] init];
	[dateFrmt setDateFormat:@"yyyy-MM-dd' 'HH:mm:ss"];
	tempTitle = self.title;
    [self setUpTabBarButtons];
    map.showsUserLocation = YES;
}
- (void)uploadPhoto: (id) sender {
    if ([UIImagePickerController isSourceTypeAvailable:UIImagePickerControllerSourceTypeCamera]) {
		NSArray* types = [UIImagePickerController availableMediaTypesForSourceType:UIImagePickerControllerSourceTypeCamera];
		for (NSString* type in types) {
			if ([type isEqualToString:@"public.image"]) {
				UIImagePickerController* uiipc = [[UIImagePickerController alloc] init];
				uiipc.sourceType = UIImagePickerControllerSourceTypeCamera;
				uiipc.mediaTypes = [NSArray arrayWithObjects:@"public.image", nil];
				uiipc.delegate = self;
				uiipc.allowsEditing = YES;
				uiipc.videoMaximumDuration = 60;
                //uiipc.videoQuality = UIImagePickerControllerQualityTypeLow;
				[self.navigationController presentModalViewController:uiipc animated:YES];
				[uiipc release];
			}
		}
	}
}

- (void)imagePickerController:(UIImagePickerController *)picker didFinishPickingMediaWithInfo:(NSDictionary *)info {
    NSDictionary* loginDict = [NSDictionary dictionaryWithContentsOfFile:[rhead_sharepoint_ipAppDelegate loginDictPath]];
	NSString* loginPassStr = [loginDict objectForKey:@"login"];
    NSDate* dateObj = [NSDate date];
   
    NSDateFormatter* dateFrm = [[NSDateFormatter alloc] init];
    [dateFrm setDateFormat:@"dd-MM-yyyy'_'HH'h'mm'm'"];
        
    NSString* filename = [NSString stringWithFormat:@"%@_%@.jpg", loginPassStr, [dateFrm stringFromDate:dateObj]];
    
    NSString* path = [[NSBundle mainBundle] pathForResource:@"UploadImage" ofType:@"txt"];
    NSString* envelope = [NSString stringWithContentsOfFile:path encoding:NSUTF8StringEncoding error:nil];
        
    UIImage* image = [info objectForKey:UIImagePickerControllerOriginalImage];
    NSData* imgData = UIImageJPEGRepresentation(image, 80);
    
    imgData = [self enterGpsData:imgData];
    
    [Base64 initialize];
    NSString* base64ImgStr = [Base64 encode:imgData];
    //NSLog(@"%@", base64ImgStr);
    NSString* uploadFolder = [currentFolder substringToIndex:[currentFolder rangeOfString:@"Pictures"].length];
    envelope = [NSString stringWithFormat:envelope, uploadFolder, @"", base64ImgStr, filename];
        
    NSURL* url = [NSURL URLWithString:@"https://www.rheadsharepoint.com/tps/_vti_bin/imaging.asmx"];
    SoapRequest* soap = [[SoapRequest alloc] initWithUrl:url username:@"myoxygen" password:@"rhead150811" domain:@"https://www.rheadsharepoint.com/tps/" delegate:self envelope:envelope action:@"http://schemas.microsoft.com/sharepoint/soap/ois/Upload"];
    [soap startRequest];
    [UIApplication sharedApplication].networkActivityIndicatorVisible = YES;
    [self.navigationController dismissModalViewControllerAnimated:YES];
}
- (void) requestFinishedWithXml: (CXMLDocument*) doc {
    NSLog(@"%@", [doc description]);
    [UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
    
    NSString* errorMsg = nil;
    int index = [[doc description] rangeOfString:@"<errorstring>"].location;
    if (index != NSNotFound) {
        errorMsg = [[doc description] substringFromIndex:index + [@"<errorstring>" length]];
        errorMsg = [errorMsg substringToIndex:[errorMsg rangeOfString:@"</errorstring>"].location];
    }
    
    UIAlertView* alert = [[UIAlertView alloc] initWithTitle:errorMsg == nil ? @"Upload successful" : @"Upload failed" message:errorMsg == nil ? @"Your image has been uploaded" : errorMsg delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles: nil];
    [alert show];
    [alert release];
    [self.navigationController popViewControllerAnimated:YES];
}
- (void) requestFinishedWithError: (NSError*) error {
    NSLog(@"%@", [error description]);
    [UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
    UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Upload failed" message:@"Your image has not been uploaded due to an unforseen error, contact your sharepoint administrator" delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles: nil];
    [alert show];
    [alert release];
}
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation {
    return YES;
}
- (void)willAnimateRotationToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation duration:(NSTimeInterval)duration {
    [self.tableView reloadData];
}
- (void)viewWillDisappear:(BOOL)animated {
	[super viewWillDisappear:animated];
	tempTitle = self.title;
	self.title = @"Back";
}
- (void)viewWillAppear:(BOOL)animated {
	[super viewWillAppear:animated];
	self.title = tempTitle;
}
- (IBAction)sortTypeAction: (id) sender {
	sltvc.keysArr = [sltvc.keysArr sortedArrayUsingComparator:(NSComparator)^(id a1, id a2){
		NSString* p1 = [[NSURL URLWithString:a1] pathExtension];
		NSString* p2 = [[NSURL URLWithString:a2] pathExtension];
		return [p1 compare: p2];
	}];
	[self.tableView reloadData];
}
- (IBAction)sortTitleAction: (id) sender {
	sltvc.keysArr = [sltvc.keysArr sortedArrayUsingComparator:(NSComparator)^(id a1, id a2){
		return [a1 compare: a2];
	}];
	[self.tableView reloadData]; 
}
- (IBAction)sortCreatedAction: (id) sender {
	sltvc.keysArr = [sltvc.keysArr sortedArrayUsingComparator:(NSComparator)^(id a1, id a2){
		NSString* p1 = [[listsData objectForKey:a1] objectForKey:@"ows_Created"];
		NSString* p2 = [[listsData objectForKey:a2] objectForKey:@"ows_Created"];
		NSDate* d1 = [dateFrmt dateFromString:p1];
		NSDate* d2 = [dateFrmt dateFromString:p2];
		return [d1 compare: d2];
	}];
	[self.tableView reloadData];
}
- (IBAction)sortModifiedAction: (id) sender {
	sltvc.keysArr = [sltvc.keysArr sortedArrayUsingComparator:(NSComparator)^(id a1, id a2){
		NSString* p1 = [[listsData objectForKey:a1] objectForKey:@"ows_Modified"];
		NSString* p2 = [[listsData objectForKey:a2] objectForKey:@"ows_Modified"];
		NSDate* d1 = [dateFrmt dateFromString:p1];
		NSDate* d2 = [dateFrmt dateFromString:p2];
		return [d1 compare: d2];
	}];
	[self.tableView reloadData];
}
- (NSData*)enterGpsData: (NSData*) imgData {
    CGImageSourceRef  source ;
    source = CGImageSourceCreateWithData((CFDataRef)imgData, NULL);
    
    //get all the metadata in the image
    NSDictionary *metadata = (NSDictionary *) CGImageSourceCopyPropertiesAtIndex(source,0,NULL);
    
    //make the metadata dictionary mutable so we can add properties to it
    NSMutableDictionary *metadataAsMutable = [[metadata mutableCopy]autorelease];
    [metadata release];
    
    NSMutableDictionary *EXIFDictionary = [[[metadataAsMutable objectForKey:(NSString *)kCGImagePropertyExifDictionary]mutableCopy]autorelease];
    NSMutableDictionary *GPSDictionary = [[[metadataAsMutable objectForKey:(NSString *)kCGImagePropertyGPSDictionary]mutableCopy]autorelease];
    if(!EXIFDictionary) {
        //if the image does not have an EXIF dictionary (not all images do), then create one for us to use
        EXIFDictionary = [NSMutableDictionary dictionary];
    }
    if(!GPSDictionary) {
        GPSDictionary = [NSMutableDictionary dictionary];
    }
    
    //Setup GPS dict
    
    float _lat = map.userLocation.coordinate.latitude;
    float _lon  = map.userLocation.coordinate.longitude;
    
    NSLog(@"%f %f", _lat, _lon);
    
    [GPSDictionary setValue:[NSNumber numberWithFloat:_lat] forKey:(NSString*)kCGImagePropertyGPSLatitude];
    [GPSDictionary setValue:[NSNumber numberWithFloat:_lon] forKey:(NSString*)kCGImagePropertyGPSLongitude];
    //[GPSDictionary setValue:lat_ref forKey:(NSString*)kCGImagePropertyGPSLatitudeRef];
    //[GPSDictionary setValue:lon_ref forKey:(NSString*)kCGImagePropertyGPSLongitudeRef];
    //[GPSDictionary setValue:[NSNumber numberWithFloat:_alt] forKey:(NSString*)kCGImagePropertyGPSAltitude];
    //[GPSDictionary setValue:[NSNumber numberWithShort:alt_ref] forKey:(NSString*)kCGImagePropertyGPSAltitudeRef]; 
    //[GPSDictionary setValue:[NSNumber numberWithFloat:_heading] forKey:(NSString*)kCGImagePropertyGPSImgDirection];
    //[GPSDictionary setValue:[NSString stringWithFormat:@"%c",_headingRef] forKey:(NSString*)kCGImagePropertyGPSImgDirectionRef];
    
    //[EXIFDictionary setValue:xml forKey:(NSString *)kCGImagePropertyExifUserComment];
    //add our modified EXIF data back into the image’s metadata
    [metadataAsMutable setObject:EXIFDictionary forKey:(NSString *)kCGImagePropertyExifDictionary];
    [metadataAsMutable setObject:GPSDictionary forKey:(NSString *)kCGImagePropertyGPSDictionary];
    
    CFStringRef UTI = CGImageSourceGetType(source); //this is the type of image (e.g., public.jpeg)
    
    //this will be the data CGImageDestinationRef will write into
    NSMutableData *dest_data = [NSMutableData data];
    
    CGImageDestinationRef destination = CGImageDestinationCreateWithData((CFMutableDataRef)dest_data,UTI,1,NULL);
    
    if(!destination) {
        NSLog(@"***Could not create image destination ***");
    }
    
    //add the image contained in the image source to the destination, overidding the old metadata with our modified metadata
    CGImageDestinationAddImageFromSource(destination,source,0, (CFDictionaryRef) metadataAsMutable);
    
    //tell the destination to write the image data and metadata into our data object.
    //It will return false if something goes wrong
    BOOL success = NO;
    success = CGImageDestinationFinalize(destination);
    
    if(!success) {
        NSLog(@"***Could not create data from image destination ***");
    }
    
    //now we have the data ready to go, so do whatever you want with it
    //here we just write it to disk at the same path we were passed
    //[dest_data writeToFile:file atomically:YES];
    
    //cleanup
    
    CFRelease(destination);
    CFRelease(source);
    
    return dest_data;
}
- (void)dealloc {
    [super dealloc];
	[tableView release];
	[sltvc release];
	[listsData release];
	[dateFrmt release];
	[myListName release];
	[titleStr release];
	[currentFolder release];
	[tempTitle release];
}

@end
