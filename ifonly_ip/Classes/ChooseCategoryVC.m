#import "ChooseCategoryVC.h"
#import <MediaPlayer/MediaPlayer.h>
#import "ifonly_ipAppDelegate.h"
#import "MainMenuVC.h"
#import "UploadVideoMenuVC.h"

@implementation ChooseCategoryVC

@synthesize avplayer, playerView, categoryPicker, category;

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
	self.category = @"Household Products";
}
- (void)playerItemDidReachEnd:(NSNotification *)notification {
	[[notification object] seekToTime:kCMTimeZero];
}
- (NSString *)pickerView:(UIPickerView *)pickerView titleForRow:(NSInteger)row forComponent:(NSInteger)component {
	switch (row) {
		case 0:
			return @"Household Products";
		case 1:
			return @"Garden Products";
		case 2:
			return @"Tools/Machinery";
		case 3:
			return @"Electrical Goods";
		case 4:
			return @"Personal Products";
		case 5:
			return @"Miscelaneous";
	}
	return @"";
}
- (void)viewDidUnload {
	[[NSNotificationCenter defaultCenter] removeObserver:self forKeyPath:AVPlayerItemDidPlayToEndTimeNotification];
}
- (IBAction) okAction: (id) sender {
	NSString* path = [ifonly_ipAppDelegate getTempMovieInfoPath];
	NSMutableDictionary* infoDict = [NSMutableDictionary dictionaryWithContentsOfFile:path];
	[infoDict setObject:self.category forKey:@"category"];
	[infoDict writeToFile:path atomically:YES];
	UploadVideoMenuVC* uvvc = [[UploadVideoMenuVC alloc] initWithNibName:nil bundle:nil];
	[self.navigationController pushViewController:uvvc animated:YES];
	[uvvc release];
}
- (IBAction) cancelAction: (id) sender {
	MainMenuVC* mmvc = [[MainMenuVC alloc] init];
	[self.navigationController pushViewController:mmvc animated:YES];
	[mmvc release];
}
- (CGFloat)pickerView:(UIPickerView *)pickerView rowHeightForComponent:(NSInteger)component {
	return 34;
}
- (void)pickerView:(UIPickerView *)pickerView didSelectRow:(NSInteger)row inComponent:(NSInteger)component {
	self.category = [self pickerView:nil titleForRow:row forComponent:0];
}
- (NSInteger)numberOfComponentsInPickerView:(UIPickerView *)pickerView {
	return 1;
}
- (NSInteger)pickerView:(UIPickerView *)pickerView numberOfRowsInComponent:(NSInteger)component {
	return 5;
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
	[category release];
	[avplayer release];
	[playerView release];
    [super dealloc];
}


@end
