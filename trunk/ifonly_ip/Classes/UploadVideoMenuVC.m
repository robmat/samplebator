#import "UploadVideoMenuVC.h"
#import "ifonly_ipAppDelegate.h"
#import "TextInputViewController.h"

@implementation UploadVideoMenuVC

@synthesize avplayer, playerView;

- (void)viewDidLoad {
	[super viewDidLoad];
	NSDictionary* tempFileInfo = [NSDictionary dictionaryWithContentsOfFile:[ifonly_ipAppDelegate getTempMovieInfoPath]];
	NSString* path = [tempFileInfo objectForKey:@"url"];
	NSURL* url = [NSURL URLWithString: path];
	avplayer = [AVPlayer playerWithURL:url];
	[playerView setPlayer:avplayer];
	playerView.backgroundColor = [UIColor colorWithRed:0 green:0 blue:0 alpha:100];
	[[NSNotificationCenter defaultCenter] addObserver:self
											 selector:@selector(playerItemDidReachEnd:)
												 name:AVPlayerItemDidPlayToEndTimeNotification
											   object:avplayer.currentItem];
	avplayer.actionAtItemEnd = AVPlayerActionAtItemEndNone;
	noteText = [[UITextField alloc] initWithFrame:CGRectZero];
	tagsText = [[UITextField alloc] initWithFrame:CGRectZero];
}
- (IBAction) aboutAction: (id) sender {

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

}
- (void)playerItemDidReachEnd:(NSNotification *)notification {
	[[notification object] seekToTime:kCMTimeZero];
}
- (void)viewDidAppear: (BOOL) animated {
	[super viewDidAppear:animated];
	[avplayer play];
}
- (void)viewDidDisappear: (BOOL) animated {
	[super viewDidDisappear:animated];
	[avplayer pause];
}
- (void)dealloc {
	[noteText release];
	[tagsText release];
    [super dealloc];
}

@end
