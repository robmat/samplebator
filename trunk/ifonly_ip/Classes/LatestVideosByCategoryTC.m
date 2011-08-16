#import "LatestVideosByCategoryTC.h"
#import "VideoCell.h"
#import "GData.h"
#import <MediaPlayer/MediaPlayer.h>

@implementation LatestVideosByCategoryTC

@synthesize dataArr, originalDataArr, navCntrl;

- (void)viewDidLoad {
    [super viewDidLoad];
}

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return 1;
}


- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return [dataArr count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    static NSString *CellIdentifier = @"VideoCell";
	GDataEntryYouTubeVideo* entry = [dataArr objectAtIndex:indexPath.row];
    VideoCell* cell = (VideoCell*) [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
    if (cell == nil) {
		NSArray *topLevelObjects = [[NSBundle mainBundle] loadNibNamed:@"VideoCell" owner:self options:nil];
		for (id currentObject in topLevelObjects){
			if ([currentObject isKindOfClass:[UITableViewCell class]]){
				cell =  (VideoCell *) currentObject;
				break;
			}
		}
	}
	[cell initializeWithGData:entry];
    return cell;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	GDataEntryYouTubeVideo* entry = [dataArr objectAtIndex:indexPath.row];
	[[UIApplication sharedApplication] openURL:[NSURL URLWithString:[[entry content] sourceURI]]];
	 /*
	NSString* description = [entry description];
	NSRange rangeStart = [description rangeOfString:@"rtsp://"];
	NSRange rangeEnd = [description rangeOfString:@" " options:NSLiteralSearch range:NSMakeRange(rangeStart.location, [description length] - rangeStart.location)];
	description = [description substringWithRange:NSMakeRange(rangeStart.location, rangeEnd.location - rangeStart.location)];
	NSURL* url = [NSURL URLWithString:description];
	NSLog(@"%@", [url description]);
	MPMoviePlayerController* moviePlayer = [[MPMoviePlayerController alloc] initWithContentURL:url];
	moviePlayer.scalingMode = MPMovieScalingModeAspectFill;
    //moviePlayer.movieControlMode = MPMovieControlModeHidden;
    [[NSNotificationCenter defaultCenter] addObserver: self
											 selector: @selector(myMovieFinishedCallback:)
												 name: MPMoviePlayerPlaybackDidFinishNotification
											   object: moviePlayer];
    [moviePlayer play];
	*/
}

-(void) myMovieFinishedCallback: (NSNotification*) aNotification {
    MPMoviePlayerController* moviePlayer = [aNotification object];
    [[NSNotificationCenter defaultCenter] removeObserver: self
													name: MPMoviePlayerPlaybackDidFinishNotification
												  object: moviePlayer];
	[moviePlayer release];
}

- (void)dealloc {
	[originalDataArr release];
	[navCntrl release];
	[dataArr release];
    [super dealloc];
}

@end
