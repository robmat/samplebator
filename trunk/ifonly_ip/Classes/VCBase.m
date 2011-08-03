#import "VCBase.h"
#import <AVFoundation/AVFoundation.h>

@implementation VCBase

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
	shouldIPlayPlak = YES;
	return [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
}
- (void)viewDidLoad {
    [super viewDidLoad];
	if (shouldIPlayPlak) {
		NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
		avPlayer = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
		[avPlayer play];
	}
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}

- (void)viewDidUnload {
    [super viewDidUnload];
}

- (void)dealloc {
    [super dealloc];
	[avPlayer release];
}

@end
