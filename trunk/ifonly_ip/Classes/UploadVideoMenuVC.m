#import "UploadVideoMenuVC.h"
#import "ifonly_ipAppDelegate.h"
#import "TextInputViewController.h"
#import "GData.h"
#import "GDataEntryYouTubeUpload.h"
#import "TeasecVC.h"
#import "MainMenuVC.h"

@implementation UploadVideoMenuVC

@synthesize avplayer, playerView, ytService, educationCategory, progressView, submitBtn;

- (void)viewDidLoad {
	[super viewDidLoad];
	NSDictionary* tempFileInfo = [NSDictionary dictionaryWithContentsOfFile:[ifonly_ipAppDelegate getTempMovieInfoPath]];
	NSString* path = [tempFileInfo objectForKey:@"url"];
	NSURL* url = [NSURL URLWithString: path];
	self.avplayer = [AVPlayer playerWithURL:url];
	[playerView setPlayer:avplayer];
	playerView.backgroundColor = [UIColor colorWithRed:0 green:0 blue:0 alpha:100];
	[[NSNotificationCenter defaultCenter] addObserver:self
											 selector:@selector(playerItemDidReachEnd:)
												 name:AVPlayerItemDidPlayToEndTimeNotification
											   object:avplayer.currentItem];
	avplayer.actionAtItemEnd = AVPlayerActionAtItemEndNone;
	noteText = [[UITextField alloc] initWithFrame:CGRectZero];
	tagsText = [[UITextField alloc] initWithFrame:CGRectZero];
	self.ytService = [ifonly_ipAppDelegate getYTService];
	[self fetchStandardCategories];
	
	UIButton* playBtn = [UIButton buttonWithType:UIButtonTypeRoundedRect];
	playBtn.frame = CGRectMake(244, 4, 72, 37);
	[playBtn setTitle:@"Play" forState:UIControlStateNormal];
	[playBtn addTarget:self action:@selector(playPauseAction:) forControlEvents:UIControlEventTouchUpInside];
	[self.view addSubview:playBtn];
}
- (void)playPauseAction: (id) sender {
	UIButton* btn = (UIButton*) sender;
	if ([btn.titleLabel.text isEqual:@"Play"]) {
		[avplayer play];
		[btn setTitle:@"Pause" forState:UIControlStateNormal];
	} else {
		[avplayer pause];
		[btn setTitle:@"Play" forState:UIControlStateNormal];
	}
}
- (void)fetchStandardCategories {
	NSURL *categoriesURL = [NSURL URLWithString:kGDataSchemeYouTubeCategory];
	GTMHTTPFetcher *fetcher = [GTMHTTPFetcher fetcherWithURL:categoriesURL];
	[fetcher setComment:@"YouTube categories"];
	[fetcher beginFetchWithDelegate:self
				  didFinishSelector:@selector(categoryFetcher:finishedWithData:error:)];
}
- (void)categoryFetcher:(GTMHTTPFetcher *)fetcher finishedWithData:(NSData *)data error:(NSError *)error {
	if (error) {
		NSLog(@"categoryFetcher:%@ failedWithError:%@", fetcher, error);
		return;
	}
	NSString *const path = @"app:categories/atom:category[yt:assignable]";
	NSXMLDocument *xmlDoc = [[[NSXMLDocument alloc] initWithData:data
														 options:0
														   error:&error] autorelease];
	NSMutableArray* categories = [NSMutableArray arrayWithCapacity:100];
	if (xmlDoc == nil) {
		NSLog(@"category fetch could not parse XML: %@", error);
	} else {
		NSArray *nodes = [xmlDoc nodesForXPath:path
										 error:&error];
		unsigned int numberOfNodes = [nodes count];
		if (numberOfNodes == 0) {
			NSLog(@"category fetch could not find nodes: %@", error);
		} else {
			for (int idx = 0; idx < numberOfNodes; idx++) {
				NSXMLElement *category = [nodes objectAtIndex:idx];
				NSString *term = [[category attributeForName:@"term"] stringValue];
				NSString *label = [[category attributeForName:@"label"] stringValue];
				if (label == nil) label = term;
				[categories addObject:term];
			}
		}
	}
	self.educationCategory = [NSString stringWithString: [categories objectAtIndex:0]];
	for (NSString* catStr in categories) {
		if ([catStr rangeOfString:@"Edu"].location != NSNotFound) {
			self.educationCategory = [NSString stringWithString: catStr];
		}
	}
}
- (IBAction) aboutAction: (id) sender {
	TeasecVC* tvc = [[TeasecVC alloc] initWithNibName:nil bundle:nil];
	[self.navigationController pushViewController:tvc animated:YES];
	[tvc release];
}
- (IBAction) addNoteAction: (id) sender {
	TextInputViewController* tivc = [[TextInputViewController alloc] initWithNibName:nil bundle:nil];
	tivc.targetTextView = noteText;
	[self.navigationController pushViewController:tivc animated:YES];
	if ([noteText.text length] == 0) {
		tivc.textView.text = @"Add note or a description.";
	} else {
		tivc.textView.text = noteText.text;
		tivc->delTextAtFirstEdit = NO;
	}
	[tivc release];
}
- (IBAction) addTagsAction: (id) sender {
	TextInputViewController* tivc = [[TextInputViewController alloc] initWithNibName:nil bundle:nil];
	tivc.targetTextView = tagsText;
	[self.navigationController pushViewController:tivc animated:YES];
	if ([tagsText.text length] == 0) {
		tivc.textView.text = @"Add keywords like the manufacturer, model, and type e.g. can opener. \n\n Separate each keyword with a comma.";
	} else {
		tivc.textView.text = tagsText.text;
		tivc->delTextAtFirstEdit = NO;
	}
	[tivc release];
}
- (IBAction) submittAction: (id) sender {
	self.submitBtn.enabled = NO;
	[self playPlak];
	NSDictionary* tempFileInfo = [NSDictionary dictionaryWithContentsOfFile:[ifonly_ipAppDelegate getTempMovieInfoPath]];
	
	GDataServiceGoogleYouTube *service = self.ytService;
	
	NSURL *url = [GDataServiceGoogleYouTube youTubeUploadURLForUserID:kGDataServiceDefaultUser];
	
	// load the file data
	NSString *path = [tempFileInfo objectForKey:@"url"];
	path = [path stringByReplacingCharactersInRange:[path rangeOfString:@"file://localhost/private"] withString:@""];
	NSFileHandle *fileHandle = [NSFileHandle fileHandleForReadingAtPath:path];
	NSString *filename = [path lastPathComponent];
	
	// gather all the metadata needed for the mediaGroup
	NSString* noteStr = noteText.text == nil ? @"" : noteText.text;
	NSString *titleStr = [NSString stringWithFormat:@"[%@] - %@", [tempFileInfo objectForKey:@"category"], noteStr];
	GDataMediaTitle *title = [GDataMediaTitle textConstructWithString:titleStr];
	
	NSString *categoryStr = [NSString stringWithString: educationCategory];
	GDataMediaCategory *category = [GDataMediaCategory mediaCategoryWithString:categoryStr];
	[category setScheme:kGDataSchemeYouTubeCategory];
	
	NSString *descStr = noteStr;
	GDataMediaDescription *desc = [GDataMediaDescription textConstructWithString:descStr];
	
	NSString *keywordsStr = tagsText.text;
	GDataMediaKeywords *keywords = [GDataMediaKeywords keywordsWithString:keywordsStr];
	
	BOOL isPrivate = NO;
	
	GDataYouTubeMediaGroup *mediaGroup = [GDataYouTubeMediaGroup mediaGroup];
	[mediaGroup setMediaTitle:title];
	[mediaGroup setMediaDescription:desc];
	[mediaGroup addMediaCategory:category];
	[mediaGroup setMediaKeywords:keywords];
	[mediaGroup setIsPrivate:isPrivate];
	
	NSString *mimeType = [GDataUtilities MIMETypeForFileAtPath:path
											   defaultMIMEType:@"video/quicktime"];
	//NSLog(@"%@ %@", path, [ifonly_ipAppDelegate getTempMovieInfoPath]);
	// create the upload entry with the mediaGroup and the file
	GDataEntryYouTubeUpload *entry;
	entry = [GDataEntryYouTubeUpload uploadEntryWithMediaGroup:mediaGroup
													fileHandle:fileHandle
													  MIMEType:mimeType
														  slug:filename];
	
	SEL progressSel = @selector(ticket:hasDeliveredByteCount:ofTotalByteCount:);
	[service setServiceUploadProgressSelector:progressSel];
	
	// YouTube's upload URL is not yet https; we need to explicitly set the
	// authorizer to allow authorizing an http URL
	//[[service authorizer] setShouldAuthorizeAllRequests:YES];
	
	GDataServiceTicket *ticket;
	ticket = [service fetchEntryByInsertingEntry:entry
									  forFeedURL:url
										delegate:self
							   didFinishSelector:@selector(uploadTicket:finishedWithEntry:error:)];
}
// progress callback
- (void)ticket:(GDataServiceTicket *)ticket hasDeliveredByteCount:(unsigned long long)numberOfBytesRead
												 ofTotalByteCount:(unsigned long long)dataLength {
	//NSLog(@"Upload: %i/%i, fraction: %f", numberOfBytesRead, dataLength, (float) numberOfBytesRead / dataLength);
	self.progressView.hidden = NO;
	self.progressView.progress = (float) numberOfBytesRead / dataLength;
}

// upload callback
- (void)uploadTicket:(GDataServiceTicket *)ticket
   finishedWithEntry:(GDataEntryYouTubeVideo *)videoEntry
               error:(NSError *)error {
	//NSLog(@"Upload finished: %@", [videoEntry title]);
	self.progressView.hidden = YES;
	if (error == nil) {
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Upload complete" 
														message:@"Your upload has been completed succesfully." 
													   delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
		[alert show];
		[alert release];
		MainMenuVC* mmvc = [[MainMenuVC alloc] initWithNibName:nil bundle:nil];
		[self.navigationController pushViewController:mmvc animated:YES];
		[mmvc release];
	} else {
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Upload error" 
														message:[error localizedDescription] 
													   delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
		[alert show];
		[alert release];
		NSLog(@"%@", [error description]);
		self.submitBtn.enabled = YES;
	}	
}
- (void)playerItemDidReachEnd:(NSNotification *)notification {
	[[notification object] seekToTime:kCMTimeZero];
}
- (void)viewDidAppear: (BOOL) animated {
	[super viewDidAppear:animated];
	//[avplayer play];
}
- (void)viewDidDisappear: (BOOL) animated {
	[super viewDidDisappear:animated];
	[avplayer pause];
}
- (void)dealloc {
	[submitBtn release];
	[progressView release];
	[ytService release];
	[noteText release];
	[tagsText release];
	[educationCategory release];
    [super dealloc];
}

@end
