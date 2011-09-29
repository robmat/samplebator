#import "MainMenuVC.h"
#import "SavedSearchVC.h"

@implementation MainMenuVC

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
    }
    return self;
}
- (IBAction)mySearchesAction {
	SavedSearchVC* ssvc = [[SavedSearchVC alloc] init];
	[self.navigationController pushViewController:ssvc animated:YES];
	[ssvc release];
}
- (IBAction)myCvsAction {
}
- (IBAction)favJobsAction {
}
- (IBAction)applicationsAction {
}
- (IBAction)myProfileAction {
}
- (IBAction)meRecruitersAction {
}
- (void)viewDidLoad {
    [super viewDidLoad];
	tabMainMenuBtn.hidden = YES;
	backBtn.hidden = YES;
}

- (void)dealloc {
    [super dealloc];
}

@end
