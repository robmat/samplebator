#import "AboutVC.h"

@implementation AboutVC

- (void)viewDidLoad {
    [super viewDidLoad];
	self.title = @"About";
}

- (void)viewWillAppear:(BOOL)animated {
	self.navigationController.navigationBarHidden = NO;
	backBtn.hidden = YES;
}

- (void)dealloc {
    [super dealloc];
}

@end
